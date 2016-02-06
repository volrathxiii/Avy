<?php

class Core extends UserFunctions {

    public $Name = "Core";
    public $triggered = false;
    public $primarytriggers = array(
        array('core'),
        array('codes'),
        array('system'),
    );
    public $triggers = array(
        'update_core' => array(
            'btn_label' => 'System Update',
            array('update'),
            array('update', 'source'),
            array('update', 'source', '4f29ea6d5a6b2c6bb205bf3a789b8cc2')
        ),
        'check_state' => array(
            'btn_label' => 'Check States',
            array('check'),
            array('check', 'states'),
            array('check', 'states', 'a3a71a26afa6457444a68dfdffeb1b5a')
        ),
        'run_cron' => array(
            array('run','cron'),
            array('run','cron','67f8c3fc1127a53e4a09c814dd8f2371')
        ),
    );
    /**
     * flow
     * - check for cron to run
     */
    public function run_cron(){
        //check for cron to run.
        $avycron = new AvyCron();
        if($runcrons = $avycron->CheckExecute()){
            foreach($runcrons as $identifier => $cron){
                $class = new $cron['class'];
                $functionname = $cron['function'];
                $class->$functionname();
                $avycron->Delete($identifier);
                unset($class, $functionname);
            }
        }
    }

    public function check_state() {
        echo "Checking States\n";
        $session = Session::getInstance();
        $SESSIONS_ = $session->Get('_STATES_');
        var_dump($SESSIONS_);
    }

    public function update_core() {
        $random_response = array(
            'Please wait while I perform system updates!',
            'Updating source codes',
            'Core files are being updated.'
        );
        TTS::Speak($random_response);
        $output = shell_exec('cd /var/www/Avy && sudo git pull');
        echo "\n---OUTPUT---\n";
        var_dump($output);
    }

}
