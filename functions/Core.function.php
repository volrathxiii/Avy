<?php

class Core extends UserFunctions {

    public $Name = "Core";
    public $triggered = false;
    public $StateStorage = "CORE";
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
        'speaker_toggle' => array(
            'btn_label' => 'Speaker',
            array('speaker','toggle','f629180510747870e7cb2f463cfd03f3')
        ),
    );
    
    public function speaker_toggle(){
        $state = $this->state->Get($this->StateStorage);
        
        if($state['speaker'] == 0){
            $state['speaker'] = 1;
            shell_exec('sudo python /var/www/Avy/bin/relay_on_1.py');
        }else{
            $state['speaker'] = 0;
            shell_exec('sudo python /var/www/Avy/bin/relay_off_1.py');
        }
        /*$current = $state['channel'];

        shell_exec("sudo irsend SEND_ONCE " . $this->Device . " KEY_CHANNELUP");

        //Add toggle capability
        //$num_padded = sprintf("%02d", $num);
        $state['channel'] = sprintf("%03s", $current + 1);
        $this->state->Set($this->StateStorage, $state);
        echo "TV Box Channel: " . $state['channel'];
         * 
         */
    }
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
        echo "STATES:";
        var_dump($session->Get('_STATES_'));
        echo "CRON:";
        var_dump($session->Get('_CRON_'));
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
