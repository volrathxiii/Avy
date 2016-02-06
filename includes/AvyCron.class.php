<?php
/**
 * Setup Cron 
 *      * /1 * * * * php /var/www/Avy/Avy.php system run cron 67f8c3fc1127a53e4a09c814dd8f2371
 * Session Format
 * [$identifier]=>Array(time, class, function)
 */
class AvyCron {
    
    protected $cronlist = false;
    
    public function __construct(){
        $session = Session::getInstance();
        $cron_session = $session->get('_CRON_');
        $this->cronlist = $cron_session;
    }
    
    /**
     * Returns cron that needs to be executed
     */
    public function CheckExecute(){
        if($this->cronlist){
            $return = array();
            foreach($this->cronlist as $identifier=>$data){
                if($data['time'] <= time()){
                    $return[$identifier] = $data;
                }
            }
            if(count($return)>0){
                return $return;
            }
        }
    }
    
    public function Get($identifier){
        $session = Session::getInstance();
        $cron_session = $session->get('_CRON_');
        if($cron_session[$identifier]){
            return $cron_session[$identifier];
        }else{
            return false;
        }
    }
    
    public function Delete($indentifier){
        $session = Session::getInstance();
        $cron_session = $session->get('_CRON_');
        unset($cron_session[ $indentifier ]);
        $session->set('_CRON_', $cron_session);
    }
    
    static public function Set($time, $class, $function, $identifier=false){
        if(!$identifier)
        {
            $identifier = md5($time+$class+$function);
        }
        $session = Session::getInstance();
        $cron_session = $session->get('_CRON_');
        $cron_session[$identifier] = array(
            'time'=>$time,
            'class'=>$class,
            'function'=>$function
            );
        $session->set('_CRON_', $cron_session);
    }
}