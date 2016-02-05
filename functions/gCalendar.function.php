<?php

require_once INCLUDES_DIR . "Google.module.php";

class gCalendar extends UserFunctions {

    public $Name = "Calendar";
    //protected $CalendarID = 'sm8m1k9uv964q2j157g920rq4';
    protected $CalendarID = 'usm8m1k9uv964q2j157g920rq4@group.calendar.google.com';
    public $primarytriggers = array(
        array('calendar'),
        array('schedule'),
        array('events'),
    );
    public $triggers = array(
        'check_schedules' => array(
            array('check'),
            'btn_label' => 'Check',
            'btn_class' => 'with-label icon',
            array('check', 'today', 'toggle', 'switch', '3a81d6b57df678d64072fcf72dd4a2ff'),
        ),
    );

    public function check_schedules() {
        $today = $this->getUpcomingScheduleToday();
        $response = array();
        if ($today) {
            foreach ($today as $key => $event) {
                $start = new DateTime($event['start']);
                //var_dump($start->format('h:i'));
                if(strpos($event['summary'],'CODING:') !== false)
                {
                    $car = str_replace("CODING:", "", $event['summary']);
                    $response[] = "Your ".$car." is on coding today! Make sure you don't forget to bring parking pass keys.";
                }else{
                    $newevent = $event['summary']." at ".$start->format('h:i');
                    if($event['location'] != ""){
                        $newevent .= " in ".$event['location'];
                    }
                    $response[] = $newevent.".";
                }
            }
            $today_response = implode(" Then ",$response)." ";
        } else {
            $today_response = "No upcoming events for today. ";
        }
        
        $response_tom = array();
        $tomorrow = $this->getUpcomingScheduleTomorrow();
        
        if ($tomorrow) {
            foreach ($tomorrow as $key => $event) {
                $start = new DateTime($event['start']);
                //var_dump($start->format('h:i'));
                if(strpos($event['summary'],'CODING:') !== false)
                {
                    $car = str_replace("CODING:", "", $event['summary']);
                    $response_tom[] = "Your ".$car." is on coding tomorrow.";
                }else{
                    $newevent = $event['summary']." at ".$start->format('h:i');
                    if($event['location'] != ""){
                        $newevent .= " in ".$event['location'];
                    }
                    $response_tom[] = $newevent.".";
                }
            }
            $tomorrow_response = "For tomorrow: ".implode(" Then ",$response_tom)." ";
        }
        
        TTS::Speak($today_response.$tomorrow_response);
    }

    public function getUpcomingScheduleToday() {
        $GoogleAPI = GoogleAppsAPI::getInstance();
        $client = $GoogleAPI->getClient();

        $service = new Google_Service_Calendar($client);
        $calendarId = $this->CalendarID;
        $optParams = array(
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => date('c'),
            'timeMax' => date('c', mktime(23, 59, 59, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year']))
        );
        $results = $service->events->listEvents($calendarId, $optParams);

        if (count($results->getItems()) == 0) {
            return false;
        } else {
            $return = array();
            foreach ($results->getItems() as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                    $end = $event->end->date;
                }
                //printf("%s (%s)\n", $event->getSummary(), $start);
                $return[] = array('summary' => $event->getSummary(), 'start' => $start, 'end' => $end, 'location'=>$event->location);
            }
            return $return;
        }
    }
    
    public function getUpcomingScheduleTomorrow() {
        $GoogleAPI = GoogleAppsAPI::getInstance();
        $client = $GoogleAPI->getClient();

        $service = new Google_Service_Calendar($client);
        $calendarId = $this->CalendarID;
        $optParams = array(
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => date('c', mktime(0, 0, 0, $this->current_date['mon'], $this->current_date['mday']+1, $this->current_date['year'])),
            'timeMax' => date('c', mktime(23, 59, 59, $this->current_date['mon'], $this->current_date['mday']+1, $this->current_date['year']))
        );
        $results = $service->events->listEvents($calendarId, $optParams);

        if (count($results->getItems()) == 0) {
            return false;
        } else {
            $return = array();
            foreach ($results->getItems() as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                    $end = $event->end->date;
                }
                //printf("%s (%s)\n", $event->getSummary(), $start);
                $return[] = array('summary' => $event->getSummary(), 'start' => $start, 'end' => $end, 'location'=>$event->location);
            }
            return $return;
        }
    }

}
