<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CoffeeMaker extends UserFunctions {

    public $Name = "CoffeeMaker";
    
    protected $CoffeeTimer = 300; //5 minutes 5*60
    
    public $primarytriggers = array(
        array('coffee'),
        array('coffee', 'maker'),
    );
    public $triggers = array(
        'make_coffee' => array(
            array('make'),
            array('make','for'),
            array('make','now'),
            array('make','now','for'),
            array('make','at'),
            array('make','at','for'),
            array('make','tomorrow','at'),
            array('make','tomorrow','at','for'),
        ),
    );

    public function make_coffee() {
        $date = getdate();
        $remind = true;
        $coffeecount = 1;
        /**
         * by default, avy will delay coffee maker for 2 minutes + cron job minute which is probably
         * less than a minute. and will then remind to manually setup coffeemaker such as water and coffee
         * including power sources. 
         */
        if($this->triggered['key'] == 0 || $this->triggered['key'] == 1)
        {
            $startTime = mktime($date['hours'], $date['minutes']+2, 0, $date['mon'], $date['mday'], $date['year']);
        }
        
        /**
         * if you said now, then set start time pass current time to start as soon as possible.
         */
        if(in_array('now',$this->triggered['triggered'])){
            $startTime = mktime($date['hours'], $date['minutes'], $date['seconds']-10, $date['mon'], $date['mday'], $date['year']);
        }
        
        /**
         * schedule coffee maker for tomorrow, add +1 to mday
         */
        if(in_array('tomorrow',$this->triggered['triggered'])){
            $date['mday'] += 1;
        }
        /**
         * at start specific time
         */
        if(in_array('at',$this->triggered['triggered'])){
            preg_match('/(?<=at )\S+/i', $this->triggered['keywords'], $timematch);

            $numbers = implode("",$timematch);
            if(strlen($numbers)>2){
                if(strlen($numbers)==3){
                    $hour = substr($numbers, 0,1);
                    $minutes = substr($numbers, 1,2);
                }else{
                    $hour = substr($numbers, 0,2);
                    $minutes = substr($numbers, 2,2);
                }
            }else{
                $hour = $numbers;
                $minutes = 0;
            }
            
            if(in_array('pm', $this->triggered['filtered'])){
                $hour += 12;
            }
            $startTime = mktime($hour, $minutes, 0, $date['mon'], $date['mday'], $date['year']);
        }
        
        /**
         * set schedule when to stop coffee maker
         */
        if(in_array('for',$this->triggered['triggered'])){
            preg_match('/(?<=for )\S+/i', $this->triggered['keywords'], $formatch);
            $coffeecount = implode("", $formatch);
        }
        
        $stopTime = $startTime + ($this->CoffeeTimer * $coffeecount);
        
        /**
         * Print and do action
         */
        if($startTime <= time())
        {
            $schedule = 'now';
            $this->start_coffee_machine();
        }else{
            $schedule = 'at '.date('h:i A', $startTime);
            if(in_array('tomorrow',$this->triggered['triggered'])){
                $schedule .= " tomorrow.";
            }else{
                $schedule .= " today.";
            }
            $this->schedule_start_coffee_machine($startTime);
        }
        
        // Schedule stop
        $this->schedule_stop_coffee_machine($stopTime);
        
        TTS::Speak( "I will make coffee for ". $coffeecount." ". $schedule );
        //echo "The coffee maker should stop at ". date('h:i A Y/m/d', $stopTime)."\n";
    }

    public function start_coffee_machine() {
        //Start coffee machine code
        echo "Starting coffee maker!\n";
    }
    
    public function stop_coffee_machine(){
        //Stop coffee machine code
        echo "Stopping coffee maker!\n";
    }
    
    public function schedule_start_coffee_machine($timestamp){
        AvyCron::Set($timestamp, 'CoffeeMaker', 'start_coffee_machine');
    }
    
    public function schedule_stop_coffee_machine($timestamp){
        AvyCron::Set($timestamp, 'CoffeeMaker', 'stop_coffee_machine');
    }

}
