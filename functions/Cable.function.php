<?php

class Cable extends UserFunctions {

    public $Name = "Cable";
    public $Device = "CABLE";
    public $StateStorage = "CABLE";
    private $volume = array('min' => 0, 'max' => 25, 'default' => 10);
    private $channel = array('min' => 0, 'max' => 211, 'default' => 12);
    public $triggered = false;
    public $primarytriggers = array(
        array('cable'),
        array('signal'),
        array('tv', 'box'),
    );
    public $default_state = array(
        'power' => 0,
        'channel' => '001',
        'volume' => 0,
        'mute' => 0,
        'target_channel' => ''
    );
    public $triggers = array(
        'togglepower' => array(
            'btn_label' => 'Power',
            'btn_class' => 'nolabel icon icon-power',
            array('power', 'on'),
            array('power', 'off'),
            array('turn', 'on'),
            array('turn', 'off'),
            array('switch', 'on'),
            array('switch', 'off'),
            array('toggle', 'turn', 'power', 'switch', 'd588102e4c8e70ef434c2bc210ac2909'),
        ),
        'setvolume' => array(
            array('set', 'volume', 'to'),
            array('adjust', 'volume', 'to'),
        ),
        'channelup' => array(
            'btn_label' => 'CH+',
            'btn_group' => 'tv-channel',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', 'up', 'a0469fc4de207ab9a56504f0d648314c'),
            array('channel', 'up'),
        ),
        'channeldown' => array(
            'btn_label' => 'CH-',
            'btn_group' => 'tv-channel',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', 'down', '8d7dcff24781dd0560751d9d51f98954'),
            array('channel', 'down'),
        ),
        'channel1' => array(
            'btn_label' => '1',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '1', 'd0d30ddd4b76f570d7e20135ac83aee6'),
            array('channel', '1'),
        ),
        'channel2' => array(
            'btn_label' => '2',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '2', '9ffd431db2dd836d1e7314298a0b0db1'),
            array('channel', '2'),
        ),
        'channel3' => array(
            'btn_label' => '3',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '3', 'e802cb4fa170e8863ae6b326e457e13f'),
            array('channel', '3'),
        ),
        'channel4' => array(
            'btn_label' => '4',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '4', '2dedb239a28b9aa865fa52e606f30c31'),
            array('channel', '4'),
        ),
        'channel5' => array(
            'btn_label' => '5',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '5', 'f02ccfa4ebdad9503d7034430e485821'),
            array('channel', '5'),
        ),
        'channel6' => array(
            'btn_label' => '6',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '6', '24a90e01aa7ad22976792d044acad2ea'),
            array('channel', '6'),
        ),
        'channel7' => array(
            'btn_label' => '7',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '7', '5096cfc18f24016366ef6315d1f8e477'),
            array('channel', '7'),
        ),
        'channel8' => array(
            'btn_label' => '8',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '8', '15014d15b2a67c81793a1bfd3284dd1f'),
            array('channel', '8'),
        ),
        'channel9' => array(
            'btn_label' => '9',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '9', 'c48c91356b2e5a9f9a197e5d67c8df98'),
            array('channel', '9'),
        ),
        'channel0' => array(
            'btn_label' => '0',
            'btn_group' => 'tv-channel-input',
            'btn_class' => 'with-label icon',
            array('toggle', 'channel', 'control', '0', 'f25fcd69939da66a83a2c06178ab0319'),
            array('channel', '0'),
        ),
        'voicechannel' => array(
            array('change', 'channel','to'),
            array('set', 'channel','to'),
            array('change', 'channel'),
            array('set', 'channel'),
        ),
    );

    public function dashboard() {
        $state = $this->state->Get($this->StateStorage);
        $p = $_GET['p'];

        $function_link = '?p=' . $p . '&f=';

        $power = $this->triggers['togglepower'];
        $return = UserInterface::Button($power['btn_label'], $function_link . 'togglepower', $power['btn_class']);

        //Volume Pan
        $return .= UserInterface::Slider("VolumePan", $function_link . "setvolume&vol=", "Volume", $state['volume'], $this->volume['max']);

        $volumes = $this->getTriggersByGroup('tv-channel');
        $volumes_group = "";
        foreach ($volumes as $function => $settings) {
            $volumes_group .= UserInterface::Button($settings['btn_label'], $function_link . $function, $settings['btn_class']);
        }
        $return .= UserInterface::Group('tv_channel', $volumes_group);
        
        //Volume
        $channels = $this->getTriggersByGroup('tv-channel-input');
        $channel_group = "";
        foreach ($channels as $function => $settings) {
            $channel_group .= UserInterface::Button($settings['btn_label'], $function_link . $function, $settings['btn_class']);
        }
        $return .= UserInterface::Group('tv-channel-inputs', $channel_group);

        return $return;
    }

    public function togglepower() {
        $state = $this->state->Get($this->StateStorage);
        $toggle = false;
        if (strpos($this->triggered['keywords'], 'd588102e4c8e70ef434c2bc210ac2909') !== FALSE) {
            if ($state['power'] == 1) {
                $toggle = 'OFF';
                $state['power'] = 0;
            } else {
                $toggle = 'ON';
                $state['power'] = 1;
            }
        }
        if ((Prompt::MatchAnswer(array('on'), $this->triggered['keywords']) && $state['power'] == 0) || $toggle == 'ON') {
            shell_exec("sudo irsend SEND_ONCE " . $this->Device . " KEY_POWER KEY_POWER");
            if ($this->speak)
                TTS::Speak("Turning TV box On");
            $state['power'] = 1;
        }
        elseif ((Prompt::MatchAnswer(array('off'), $this->triggered['keywords']) && $state['power'] == 1) || $toggle == 'OFF') {
            if ($this->speak)
                TTS::Speak("Turning TV box Off");
            $state['power'] = 0;
            shell_exec("sudo irsend SEND_ONCE " . $this->Device . " KEY_POWER KEY_POWER");
        }
        //Add toggle capability
        $this->state->Set($this->StateStorage, $state);
    }

    public function setvolume() {
        echo "Function: setvolume \n";
        $state = $this->state->Get($this->StateStorage);

        // Check if device is powered ON
        if ($state['power'] == 1) {
            $current_volume = $state['volume'];
            /**
             * @todo add a function for this
             */
            $name = implode(" ", $this->primarytriggers[array_rand($this->primarytriggers, 1)]);

            $filtered = $this->removeKeywords($this->triggered['filtered']);
            if (isset($_GET['vol'])) {
                $state['volume'] = $_GET['vol'];
                $value = $_GET['vol'];
            }

            if ($findkey = array_search('to', $this->triggered['triggered'])) {
                if (!isset($value)) {
                    $numberwords = implode(" ", $filtered);
                    $value = $this->wordsToNumber($numberwords);
                }
                if ($value > $current_volume) {
                    if ($value <= $this->volume['max']) {
                        $valuediff = $value - $current_volume;
                        $state['volume'] = $value;
                    } else {
                        $valuediff = $this->volume['max'] - $current_volume;
                        $state['volume'] = $this->volume['max'];
                    }
                    $volume_function = 'KEY_VOLUMEUP';
                    //Do volume up $valuediff number of press
                } elseif ($value < $current_volume) {
                    /**
                     * @todo add if negative value is used
                     */
                    if ($value >= $this->volume['min']) {
                        $valuediff = $current_volume - $value;
                        $state['volume'] = $value;
                    }
                    $volume_function = 'KEY_VOLUMEDOWN';
                    //Do volume up $valuediff number of press
                }
                $runfunc = "";
                /*
                 * sleep .5; irsend SEND_ONCE PIONEER_AVR KEY_VOLUMEDOWN;
                 */
                for ($i = 1; $i <= $valuediff; $i++) {
                    $runfunc .= "sleep .5; irsend SEND_ONCE " . $this->Device . " " . $volume_function . ";";
                }

                TTS::Speak("Setting " . $name . " volume to " . $state['volume']);
                shell_exec($runfunc);
                $this->state->Set($this->StateStorage, $state);
            }
        } else {
            TTS::Speak("TV box is currently off.");
        }
    }

    /**
     * no tts
     */
    public function channelup() {
        $state = $this->state->Get($this->StateStorage);
        $current = $state['channel'];

        shell_exec("sudo irsend SEND_ONCE " . $this->Device . " KEY_CHANNELUP");

        //Add toggle capability
        //$num_padded = sprintf("%02d", $num);
        $state['channel'] = sprintf("%03s", $current + 1);
        $this->state->Set($this->StateStorage, $state);
        echo "TV Box Channel: " . $state['channel'];
    }

    public function channeldown() {
        $state = $this->state->Get($this->StateStorage);
        $current = $state['channel'];

        shell_exec("sudo irsend SEND_ONCE " . $this->Device . " KEY_CHANNELDOWN");

        //Add toggle capability
        $state['channel'] = sprintf("%03s", $current - 1);
        $this->state->Set($this->StateStorage, $state);
        echo "TV Box Channel: " . $state['channel'];
    }
    
    public function voicechannel()
    {
        $state = $this->state->Get($this->StateStorage);
        $target = end($this->triggered['filtered']);
        if($target > $this->channel['min'] && $target <= $this->channel['max']){
            $state['target_channel'] = $target;
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    
    public function switchtotargetchannel()
    {
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $split = str_split($target_channel);
        $runfunc = "";
        foreach($split as $value){
            $runfunc .= "sleep .5; irsend SEND_ONCE " . $this->Device . " KEY_NUMERIC_" . $value . ";";
        }
        shell_exec($runfunc);
        $state['channel'] = $target_channel;
        $state['target_channel'] = '';
        $this->state->Set($this->StateStorage, $state);
    }

    public function channel1() {
        $channel_pressed = 1;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    public function channel2() {
        $channel_pressed = 2;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    public function channel3() {
        $channel_pressed = 3;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    public function channel4() {
        $channel_pressed = 4;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    public function channel5() {
        $channel_pressed = 5;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    public function channel6() {
        $channel_pressed = 6;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    public function channel7() {
        $channel_pressed = 7;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    public function channel8() {
        $channel_pressed = 8;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    public function channel9() {
        $channel_pressed = 9;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
    public function channel0() {
        $channel_pressed = 0;
        $state = $this->state->Get($this->StateStorage);
        $target_channel = $state['target_channel'];
        $state['target_channel'] = $target_channel . $channel_pressed;
        echo $state['target_channel'];
        if (strlen($state['target_channel'] ) >= 3) {
            //set channel here
            $this->state->Set($this->StateStorage, $state);
            $this->switchtotargetchannel();
            $state['target_channel'] = '';
        }
        $this->state->Set($this->StateStorage, $state);
    }
}
