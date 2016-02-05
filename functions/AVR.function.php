<?php

/**
 * Pattern or Procedure
 * Check if song/playlist is in the media folder -
 * If media folder is not set this is not set Avy will ask for folder
 */
class AVR extends UserFunctions {

    private $volume = array('min' => 0, 'max' => 80, 'default' => 30);
    public $Name = "AV Reciever";
    public $triggered = false;
    public $primarytriggers = array(
        array('avr'),
        array('amplifier'),
        array('sound', 'system'),
        array('reciever')
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
            array('toggle', 'turn', 'power', 'switch', 'bc61a9a270f87720016b38d0f0580a70'),
        ),
        'set_source_bd' => array(
            array('switch', 'to', 'blueray'),
            array('set', 'to', 'blueray'),
            array('source', 'to', 'blueray'),
            'btn_label' => 'BD',
            'btn_group' => 'Source',
            'btn_class' => 'with-label icon icon-inputs icon-blueray',
            array('source', 'to', 'blueray', '00c35245d2c20e144afc13c92e382786'),
        ),
        'set_source_dvd' => array(
            array('switch', 'to', 'dvd'),
            array('set', 'to', 'dvd'),
            array('source', 'to', 'dvd'),
            array('source', 'to', 'dvd', 'f939045ad00935b3bb018b174cb53978'),
            'btn_label' => 'DVD',
            'btn_group' => 'Source',
            'btn_class' => 'with-label icon icon-inputs icon-dvd',
        ),
        'set_source_cd' => array(
            array('switch', 'to', 'cd'),
            array('set', 'to', 'cd'),
            array('source', 'to', 'cd'),
            array('source', 'to', 'cd', '95b23f20abbd3fcc00a94ed3dfb0bb50'),
            'btn_label' => 'CD',
            'btn_group' => 'Source',
            'btn_class' => 'with-label icon icon-inputs icon-cd',
        ),
        'set_source_tuner' => array(
            array('switch', 'to', 'tuner'),
            array('set', 'to', 'tuner'),
            array('source', 'to', 'tuner'),
            array('source', 'to', 'tuner', 'bde557bc28bee27843307ae0598300a7'),
            'btn_label' => 'Tuner',
            'btn_group' => 'Source',
            'btn_class' => 'with-label icon icon-inputs icon-tuner',
        ),
        'set_source_usb' => array(
            array('switch', 'to', 'usb'),
            array('set', 'to', 'usb'),
            array('source', 'to', 'usb'),
            array('source', 'to', 'usb', '09e13527e9d47992967fac4145112ede'),
            'btn_label' => 'USB',
            'btn_group' => 'Source',
            'btn_class' => 'with-label icon icon-inputs icon-usb',
        ),
        'set_source_cable' => array(
            array('switch', 'to', 'cable'),
            array('set', 'to', 'cable'),
            array('source', 'to', 'cable'),
            array('source', 'to', 'cable', '7f30eb133d3e4a913bf5197a9dbafef0'),
            'btn_label' => 'Cable',
            'btn_group' => 'Source',
            'btn_class' => 'with-label icon icon-inputs icon-cable',
        ),
        'set_source_game' => array(
            array('switch', 'to', 'game'),
            array('set', 'to', 'game'),
            array('source', 'to', 'game'),
            array('source', 'to', 'game', '2afeecea236a24e345f0912df68fa052'),
            'btn_label' => 'Game',
            'btn_group' => 'Source',
            'btn_class' => 'with-label icon icon-inputs icon-game',
        ),
        'set_source_tv' => array(
            array('switch', 'to', 'tv'),
            array('set', 'to', 'tv'),
            array('source', 'to', 'tv'),
            array('source', 'to', 'tv', '882282f3221cf14dd10877f02f662df3'),
            'btn_label' => 'TV',
            'btn_group' => 'Source',
            'btn_class' => 'with-label icon icon-inputs icon-tv',
        ),
        'power_subwoofer' => array(
            'btn_label' => 'Subwoofer',
            'btn_class' => 'nolabel icon icon-power icon-subwoofer',
            array('power', 'on', 'subwoofer'),
            array('power', 'off', 'subwoofer'),
            array('turn', 'on', 'subwoofer'),
            array('turn', 'off', 'subwoofer'),
            array('switch', 'on', 'subwoofer'),
            array('switch', 'off', 'subwoofer'),
            array('toggle', 'subwoofer', 'power', 'ffb8c7c5755626f0d56076c0cb60292a'),
        ),
        'volume_increase' => array(
            array('increase', 'volume'),
            array('increase', 'volume', 'by'),
            array('set', 'volume', 'up'),
            array('set', 'volume', 'up', 'by'),
            'btn_label' => 'Up',
            'btn_group' => 'Volume',
            'btn_class' => 'with-label icon ',
            array('increase', 'volume', 'dashboard_', '_button', 'b66a5ef8ad905270794b610887df24df'),
        ),
        'volume_decrease' => array(
            array('decrease', 'volume'),
            array('decrease', 'volume', 'by'),
            array('set', 'volume', 'down'),
            array('set', 'volume', 'down', 'by'),
            'btn_label' => 'Down',
            'btn_group' => 'Volume',
            'btn_class' => 'with-label icon ',
            array('decrease', 'volume', 'dashboard_', '_button', 'a42462d48e9d4a6a44f719ad0a84eced'),
        ),
        'set_audiomode' => array(
            array('set', 'audio'),
            array('set', 'audio', 'mode'),
            array('set', 'direct'),
            array('set', 'pure', 'direct'),
            array('set', 'auto', 'surround'),
            array('switch', 'audio'),
            array('switch', 'audio', 'mode'),
            array('switch', 'direct'),
            array('switch', 'pure', 'direct'),
            array('switch', 'auto', 'surround'),
            'btn_label' => 'Output',
            'btn_group' => 'AudioMode',
            'btn_class' => 'with-label icon',
            array('output', 'audio', 'mode', 'toggle', '23eeecfdc135dd4647c42bfd069c088d')
        ),
        /**
         * 'FS Advance',
          'Action',
          'Drama',
          'Entertianment Show',
          'Advanced Game',
          'Sports',
          'Classical',
          'Pop/Rock',
          'Unplugged'
         */
        'set_advance_surround' => array(
            array('set', 'surround', 'stereo'),
            array('set', 'surround', 'advance', 'stereo'),
            array('set', 'surround', 'advanced', 'stereo'),
            array('set', 'surround', 'fs'),
            array('set', 'surround', 'front', 'stage'),
            array('set', 'surround', 'drama'),
            array('set', 'surround', 'action'),
            array('set', 'surround', 'entertainment'),
            array('set', 'surround', 'entertainment', 'show'),
            array('set', 'surround', 'game'),
            array('set', 'surround', 'game', 'advance'),
            array('set', 'surround', 'game', 'advanced'),
            array('set', 'surround', 'sports'),
            array('set', 'surround', 'classical'),
            array('set', 'surround', 'pop'),
            array('set', 'surround', 'rock'),
            array('set', 'surround', 'unplugged'),
            'btn_label' => 'Surround',
            'btn_group' => 'surround-sound',
            'btn_class' => 'with-label icon',
            array('output', 'surround', 'sound', 'option', 'toggle', 'be1cae78e00bc6118ba62904d892ef61')
        ),
        'set_alc_toggle' => array(
            array('set', 'stereo'),
            array('set', 'stereo', 'alc'),
            array('set', 'dolby'),
            array('set', 'dolby', 'movie'),
            array('set', 'dolby', 'music'),
            array('set', 'dolby', 'game'),
            array('set', 'neo', 'cinema'),
            array('set', 'neo', 'music'),
            array('switch', 'stereo'),
            array('switch', 'stereo', 'alc'),
            array('switch', 'dolby'),
            array('switch', 'dolby', 'movie'),
            array('switch', 'dolby', 'music'),
            array('switch', 'dolby', 'game'),
            array('switch', 'neo', 'cinema'),
            array('switch', 'neo', 'music'),
            'btn_label' => 'ALC',
            'btn_group' => 'level-control',
            'btn_class' => 'with-label icon',
            array('output', 'auto', 'level', 'contorl', 'toggle', 'e34ab6fc572dc976f587d5f6662f1159')
        ),
        'setvolume' => array(
            array('set', 'volume', 'to'),
            array('adjust', 'volume', 'to'),
        ),
    );
    public $default_state = array(
        'power' => 0,
        'source' => 'CD',
        'volume' => 0,
        'surround' => 0,
        'stereo' => 0,
        'subwoofer' => 0,
        'mute' => 0,
        'alc' => 0,
        'audiomode' => 0
    );
    protected $random_responses = array(
        'setting' => array('Setting', 'Switching', 'Changing'),
        'names' => array('AVR', 'Amplifier', 'Sound System', 'Reciever'),
        'sources' => array('source', 'input', 'mode'),
    );
    protected $audio_mode = array(
        'Direct',
        'Pure Direct',
        'Auto Surround'
    );
    protected $alc = array(
        'Stereo',
        'Stereo ALC',
        'Dolby Pro Logic',
        'Dolby Pro Logic Movie',
        'Dolby Pro Logic Music',
        'Dolby Pro Logic Game',
        'NEO06 Cinema',
        'NEO06 Music'
    );
    protected $advance_surround = array(
        'Extreme Stereo',
        'FS Advance',
        'Action',
        'Drama',
        'Entertianment Show',
        'Advanced Game',
        'Sports',
        'Classical',
        'Pop/Rock',
        'Unplugged'
    );

    /** Overrides default display of functions for dashboard * */

    /**
     * @todo display states
     * @return type
     */
    public function dashboard() {
        $state = $this->state->Get('AVR');
        $p = $_GET['p'];
        if (isset($state['source'])) {
            $current_function = $this->getFunctionByLabel($state['source']);
        } else {
            $current_function = "";
        }

        $function_link = '?p=' . $p . '&f=';

        //Power Button
        $power = $this->triggers['togglepower'];
        $return = UserInterface::Button($power['btn_label'], $function_link . 'togglepower', $power['btn_class']);

        //Volume
        $volumes = $this->getTriggersByGroup('Volume');
        $volumes_group = "";
        foreach ($volumes as $function => $settings) {
            $volumes_group .= UserInterface::Button($settings['btn_label'], $function_link . $function, $settings['btn_class']);
        }
        $return .= UserInterface::Group('AVR_Volume', $volumes_group);

        //Volume Pan
        $return .= UserInterface::Slider("VolumePan", $function_link . "setvolume&vol=", "Volume", $state['volume'], 80);

        //Sources
        $sources = $this->getTriggersByGroup('Source');
        $SourcesList = array();
        foreach ($sources as $function => $settings) {
            $SourcesList[$function] = UserInterface::Button($settings['btn_label'], $function_link . $function, $settings['btn_class']);
        }

        $return .= UserInterface::Dropdown('Source', $SourcesList, 'Sources', 'function-btn icon', $current_function);

        // Audio Mode
        $audiomode = $this->triggers['set_audiomode'];
        $return .= UserInterface::Button($audiomode['btn_label'], $function_link . 'set_audiomode', $audiomode['btn_class']);

        // Audio Level Control
        $alc = $this->triggers['set_alc_toggle'];
        $return .= UserInterface::Button($alc['btn_label'], $function_link . 'set_alc_toggle', $alc['btn_class']);
        
        // Surround
        $surround = $this->triggers['set_advance_surround'];
        $return .= UserInterface::Button($surround['btn_label'], $function_link . 'set_advance_surround', $surround['btn_class']);
        
        //Subwoofer Button
        $subwoofer = $this->triggers['power_subwoofer'];
        $return .= UserInterface::Button($subwoofer['btn_label'], $function_link . 'power_subwoofer', $subwoofer['btn_class']);

        return $return;
    }

    /**
     * AVR Power
     */
    public function togglepower() {
        $state = $this->state->Get('AVR');
        $toggle = false;
        if (strpos($this->triggered['keywords'], 'bc61a9a270f87720016b38d0f0580a70') !== FALSE) {
            if ($state['power'] == 1) {
                $toggle = 'OFF';
                $state['power'] = 0;
            } else {
                $toggle = 'ON';
                $state['power'] = 1;
            }
        }
        if ((Prompt::MatchAnswer(array('on'), $this->triggered['keywords']) && $state['power'] == 0) || $toggle == 'ON') {
            shell_exec("sudo irsend SEND_ONCE PIONEER_AVR KEY_POWER KEY_POWER");
            if ($this->speak)
                TTS::Speak("Turning Sound System On");
            $state['power'] = 1;
        }
        elseif ((Prompt::MatchAnswer(array('off'), $this->triggered['keywords']) && $state['power'] == 1) || $toggle == 'OFF') {
            if ($this->speak)
                TTS::Speak("Turning Sound System Off");
            $state['power'] = 0;
            shell_exec("sudo irsend SEND_ONCE PIONEER_AVR KEY_POWER KEY_POWER");
        }
        //Add toggle capability
        $this->state->Set('AVR', $state);
    }

    /**
     * Set {AVR} volume to {number}
     * @todo add jquery input slider for dashboard
     */
    public function setvolume() {
        echo "Function: setvolume \n";
        $state = $this->state->Get('AVR');

        // Check if AVR is powered ON
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
                    $runfunc .= "sleep .5; irsend SEND_ONCE PIONEER_AVR " . $volume_function . ";";
                }

                TTS::Speak("Setting " . $name . " volume to " . $state['volume']);
                shell_exec($runfunc);
                $this->state->Set('AVR', $state);
            }
        } else {
            TTS::Speak("Sound System is currently off.");
        }
    }

    /**
     * Increase {AVR} volume
     * increase {AVR} volume by {number}
     * set {AVR} volume higher
     * set {AVR} volume higher by {number}
     */
    public function volume_increase() {
        echo "Function: volume_increase \n";
        $state = $this->state->Get('AVR');
        $current_volume = $state['volume'];
        /**
         * @todo add a function for this
         */
        $name = implode(" ", $this->primarytriggers[array_rand($this->primarytriggers, 1)]);

        if ($current_volume >= $this->volume['max']) {

            TTS::Speak($name . ' volume maxed!');
        } else {
            //Remove words that is from keywords
            $filtered = $this->removeKeywords($this->triggered['filtered']);

            //Check if value is set BY {number}
            if ($findkey = array_search('by', $this->triggered['triggered'])) {
                $numberwords = implode(" ", $filtered);
                $value = $this->wordsToNumber($numberwords);
                TTS::Speak("Increasing volume of " . $name);
            } else {
                $value = 1;
            }

            if ($current_volume + $value >= $this->volume['max']) {
                $state['volume'] = $this->volume['max'];
            } else {
                $state['volume'] = $current_volume + $value;
            }
            $this->state->Set('AVR', $state);

            /**
             * SEND IR VOLUME UP NUMBER OF VALUES
             */
        }
    }

    public function volume_decrease() {
        echo "Function: volume_decrease \n";
        $state = $this->state->Get('AVR');
        $current_volume = $state['volume'];
        /**
         * @todo add a function for this
         */
        $name = implode(" ", $this->primarytriggers[array_rand($this->primarytriggers, 1)]);

        if ($current_volume <= $this->volume['min']) {

            TTS::Speak($name . ' volume lowest!');
        } else {
            //Remove words that is from keywords
            $filtered = $this->removeKeywords($this->triggered['filtered']);

            //Check if value is set BY {number}
            if ($findkey = array_search('by', $this->triggered['triggered'])) {
                $numberwords = implode(" ", $filtered);
                $value = $this->wordsToNumber($numberwords);
                TTS::Speak("Decreasing volume of " . $name);
            } else {
                $value = 1;
            }

            if ($current_volume - $value <= $this->volume['min']) {
                $state['volume'] = $this->volume['min'];
            } else {
                $state['volume'] = $current_volume - $value;
            }
            $this->state->Set('AVR', $state);

            /**
             * SEND IR VOLUME DOWN NUMBER OF VALUES
             */
        }
    }

    /**
     * @deprecated since version 1
     * Triggers has been remove this will never be called just for reference
     */
    public function switch_to() {
        $state = $this->state->Get('AVR');

        foreach ($this->switch_keywords as $function_name => $func_keys) {
            foreach ($func_keys as $key) {
                $search = array_search($key, $this->triggered['filtered']);
                if ($search) {
                    $func_call = $this->$function_name();
                    echo "AVR set Source to: " . $func_call;

                    if ($this->speak)
                        TTS::Speak("Switching Sound System to " . $func_call);

                    $state['source'] = $func_call;
                    $this->state->Set('AVR', $state);
                    return $func_call;
                }
            }
        }

        //TTS
        echo "No avr source found in your command: '" . $this->triggered['keywords'] . "'\n";
        //return;
        //var_dump($parameters['trigger'][2]);
    }

    protected function response_set_source($source) {
        return $this->random_responses['setting'][array_rand($this->random_responses['setting'], 1)] . " " .
                $this->random_responses['names'][array_rand($this->random_responses['names'], 1)] . " " .
                $this->random_responses['sources'][array_rand($this->random_responses['sources'], 1)] . " " .
                "to " . strtoupper($source);
    }

    /**
     * Set Source Blueray
     */
    public function set_source_bd() {
        $state = $this->state->Get('AVR');
        if ($this->speak) {
            TTS::Speak($this->response_set_source('Blueray'));
        }
        $state['source'] = 'Blueray';
        //Trigger IR here
        shell_exec('irsend SEND_ONCE PIONEER_AVR KEY_BLUE KEY_BLUE');
        $this->state->Set('AVR', $state);
    }

    /**
     * Set Source DVD
     */
    public function set_source_dvd() {
        $state = $this->state->Get('AVR');
        if ($this->speak) {
            TTS::Speak($this->response_set_source('DVD'));
        }
        $state['source'] = 'DVD';
        //Trigger IR here
        shell_exec('irsend SEND_ONCE PIONEER_AVR KEY_DVD KEY_DVD');
        $this->state->Set('AVR', $state);
    }

    /**
     * Set Source CD
     */
    public function set_source_cd() {
        $state = $this->state->Get('AVR');
        if ($this->speak) {
            TTS::Speak($this->response_set_source('CD'));
        }
        $state['source'] = 'CD';
        //Trigger IR here
        shell_exec('irsend SEND_ONCE PIONEER_AVR KEY_CD KEY_CD');
        $this->state->Set('AVR', $state);
    }

    /**
     * Set Source Tuner
     */
    public function set_source_tuner() {
        $state = $this->state->Get('AVR');
        if ($this->speak) {
            TTS::Speak($this->response_set_source('Tuner'));
        }
        $state['source'] = 'Tuner';
        //Trigger IR here
        shell_exec('irsend SEND_ONCE PIONEER_AVR KEY_TUNER KEY_TUNER');
        $this->state->Set('AVR', $state);
    }

    /**
     * Set Source USB
     */
    public function set_source_usb() {
        $state = $this->state->Get('AVR');
        if ($this->speak) {
            TTS::Speak($this->response_set_source('USB'));
        }
        $state['source'] = 'USB';
        //Trigger IR here
        shell_exec('irsend SEND_ONCE PIONEER_AVR KEY_MEDIA KEY_MEDIA');
        $this->state->Set('AVR', $state);
    }

    /**
     * Set Source Cable
     */
    public function set_source_cable() {
        $state = $this->state->Get('AVR');
        if ($this->speak) {
            TTS::Speak($this->response_set_source('Cable'));
        }
        $state['source'] = 'Cable';
        //Trigger IR here
        shell_exec('irsend SEND_ONCE PIONEER_AVR KEY_SAT KEY_SAT');
        $this->state->Set('AVR', $state);
    }

    /**
     * Set Source Game
     */
    public function set_source_game() {
        $state = $this->state->Get('AVR');
        if ($this->speak) {
            TTS::Speak($this->response_set_source('Game'));
        }
        $state['source'] = 'Game';
        //Trigger IR here
        shell_exec('irsend SEND_ONCE PIONEER_AVR KEY_GAMES KEY_GAMES');
        $this->state->Set('AVR', $state);
    }

    /**
     * Set Source TV
     */
    public function set_source_tv() {
        $state = $this->state->Get('AVR');
        if ($this->speak) {
            TTS::Speak($this->response_set_source('TV'));
        }
        $state['source'] = 'TV';
        //Trigger IR here
        shell_exec('irsend SEND_ONCE PIONEER_AVR KEY_TV KEY_TV');
        $this->state->Set('AVR', $state);
    }

    /**
     * Power Subwoofer
     */
    public function power_subwoofer() {
        $state = $this->state->Get('AVR');
        $toggle = false;
        if (strpos($this->triggered['keywords'], 'ffb8c7c5755626f0d56076c0cb60292a') !== FALSE) {
            if ($state['subwoofer'] == 1) {
                $toggle = 'OFF';
            } else {
                $toggle = 'ON';
            }
        }

        if ((Prompt::MatchAnswer(array('on'), $this->triggered['keywords']) && $state['subwoofer'] == 0) || $toggle == 'ON') {
            if ($this->speak)
                TTS::Speak("Turning Subwoofer On");
            $state['subwoofer'] = 1;
        }
        elseif ((Prompt::MatchAnswer(array('off'), $this->triggered['keywords']) && $state['subwoofer'] == 1) || $toggle == 'OFF') {
            if ($this->speak)
                TTS::Speak("Turning Subwoofer Off");
            $state['subwoofer'] = 0;
        }

        //var_dump($this->triggered['keywords']);
        $this->state->Set('AVR', $state);
    }

    protected function response_set_general($source) {
        return $this->random_responses['setting'][array_rand($this->random_responses['setting'], 1)] . " " .
                $this->random_responses['names'][array_rand($this->random_responses['names'], 1)] . " " .
                "to " . strtoupper($source);
    }

    /**
     * AVR Set Direct
     */
    public function set_audiomode() {
        $state = $this->state->Get('AVR');
        $current_audiomode = $state['audiomode'];
        $maxmode = (count($this->audio_mode));
        $function = "KEY_F1";
        if (in_array("pure", $this->triggered['triggered'])) {
            $target = 1;
        } elseif (in_array("surround", $this->triggered['triggered'])) {
            $target = 2;
        } elseif (in_array("direct", $this->triggered['triggered'])) {
            $target = 0;
        } elseif (in_array("23eeecfdc135dd4647c42bfd069c088d", $this->triggered['triggered'])) {
            //Do toggle only
            if ($current_audiomode >= $maxmode - 1) {
                $target = 0;
            } else {
                $target = $current_audiomode + 1;
            }
        }
        if ($target == $current_audiomode)
            return true;
        /** Get looping keys based on current selected */
        $toggleCount = 0;
        for ($i = $current_audiomode + 1; $i <= count($this->audio_mode); $i++) {
            //if $i reaches the max array count go back to 0
            if ($i == $maxmode) {
                $i = 0;
            }

            $toggleCount += 1;
            if ($i == $target) {
                // Do action
                $runfunc = "sleep .5; irsend SEND_ONCE PIONEER_AVR " . $function . ";";
                for ($s = 0; $s < $toggleCount; $s++) {
                    $runfunc .= "sleep .5; irsend SEND_ONCE PIONEER_AVR " . $function . ";";
                }
                if ($this->speak)
                    TTS::Speak($this->response_set_general('audio mode: ' . $this->audio_mode[$target]));
                $state['audiomode'] = $target;
                shell_exec($runfunc);
                $this->state->Set('AVR', $state);
                break;
            }

            //Stop when counter goes back to current audio mode
            if ($i == $current_audiomode)
                break;
        }
    }

    /**
     * AVR Set ALC
     * protected $alc = array(
      0'Stereo',
      1'Stereo ALC',
      2'Dolby Pro Logic',
      3'Dolby Pro Logic Movie',
      4'Dolby Pro Logic Music',
      5'Dolby Pro Logic Game',
      6'NEO06 Cinema',
      7'NEO06 Music'
      );
     */
    public function set_alc_toggle() {
        $state = $this->state->Get('AVR');
        $current_alc = $state['alc'];
        $maxmode = (count($this->alc));
        $function = "KEY_F3";
        if (in_array("alc", $this->triggered['triggered'])) {
            $target = 1;
        } elseif (in_array("stereo", $this->triggered['triggered'])) {
            $target = 0;
        } elseif (in_array("movie", $this->triggered['triggered'])) {
            $target = 3;
        } elseif (in_array("music", $this->triggered['triggered']) && in_array("dolby", $this->triggered['triggered'])) {
            $target = 4;
        } elseif (in_array("game", $this->triggered['triggered'])) {
            $target = 5;
        } elseif (in_array("dolby", $this->triggered['triggered'])) {
            $target = 2;
        } elseif (in_array("cinema", $this->triggered['triggered'])) {
            $target = 6;
        } elseif (in_array("music", $this->triggered['triggered']) && in_array("neo", $this->triggered['triggered'])) {
            $target = 7;
        } elseif (in_array("e34ab6fc572dc976f587d5f6662f1159", $this->triggered['triggered'])) {
            //Do toggle only
            if ($current_alc >= $maxmode - 1) {
                $target = 0;
            } else {
                $target = $current_alc + 1;
            }
        }

        if ($target == $current_alc) {
            return true;
        }

        /** Get looping keys based on current selected */
        $toggleCount = 0;
        for ($i = $current_alc + 1; $i <= count($this->alc); $i++) {
            //if $i reaches the max array count go back to 0
            if ($i == $maxmode) {
                $i = 0;
            }

            $toggleCount += 1;
            if ($i == $target) {
                // Do action
                $runfunc = "sleep .5; irsend SEND_ONCE PIONEER_AVR " . $function . ";";
                for ($s = 0; $s < $toggleCount; $s++) {
                    $runfunc .= "sleep .5; irsend SEND_ONCE PIONEER_AVR " . $function . ";";
                }
                if ($this->speak)
                    TTS::Speak($this->response_set_general('level control: ' . $this->alc[$target]));
                $state['alc'] = $target;
                shell_exec($runfunc);
                $this->state->Set('AVR', $state);
                break;
            }

            //Stop when counter goes back to current audio mode
            if ($i == $current_alc)
                break;
        }
    }

    /**
     * 
     * 0'extreme stereo'
     * 1'FS Advance',
      2'Action',
      3'Drama',
      4'Entertianment Show',
      5'Advanced Game',
      6'Sports',
      7'Classical',
      8'Pop/Rock',
      9'Unplugged'
     */
    public function set_advance_surround() {
        $state = $this->state->Get('AVR');
        $current = $state['surround'];
        $maxmode = (count($this->advance_surround));
        $function = "KEY_F4";
        if (in_array("stereo", $this->triggered['triggered'])) {
            $target = 0;
        } elseif (in_array("fs", $this->triggered['triggered']) || in_array("front", $this->triggered['triggered'])) {
            $target = 1;
        } elseif (in_array("action", $this->triggered['triggered'])) {
            $target = 2;
        } elseif (in_array("drama", $this->triggered['triggered'])) {
            $target = 3;
        } elseif (in_array("entertainment", $this->triggered['triggered'])) {
            $target = 4;
        } elseif (in_array("game", $this->triggered['triggered'])) {
            $target = 5;
        } elseif (in_array("sports", $this->triggered['triggered']) || in_array("sport", $this->triggered['triggered'])) {
            $target = 6;
        } elseif (in_array("classical", $this->triggered['triggered'])) {
            $target = 7;
        } elseif (in_array("pop", $this->triggered['triggered']) || in_array("rock", $this->triggered['triggered'])) {
            $target = 8;
        } elseif (in_array("unplugged", $this->triggered['triggered'])) {
            $target = 9;
        } elseif (in_array("be1cae78e00bc6118ba62904d892ef61", $this->triggered['triggered'])) {
            //Do toggle only
            if ($current >= $maxmode - 1) {
                $target = 0;
            } else {
                $target = $current + 1;
            }
        }

        if ($target == $current) {
            return true;
        }

        /** Get looping keys based on current selected */
        $toggleCount = 0;
        for ($i = $current + 1; $i <= count($this->advance_surround); $i++) {
            //if $i reaches the max array count go back to 0
            if ($i == $maxmode) {
                $i = 0;
            }

            $toggleCount += 1;
            if ($i == $target) {
                // Do action
                $runfunc = "sleep .5; irsend SEND_ONCE PIONEER_AVR " . $function . ";";
                for ($s = 0; $s < $toggleCount; $s++) {
                    $runfunc .= "sleep .5; irsend SEND_ONCE PIONEER_AVR " . $function . ";";
                }
                if ($this->speak)
                    TTS::Speak($this->response_set_general('surround: ' . $this->advance_surround[$target]));
                $state['surround'] = $target;
                shell_exec($runfunc);
                //var_dump($this->advance_surround[$target]);
                $this->state->Set('AVR', $state);
                break;
            }

            //Stop when counter goes back to current audio mode
            if ($i == $current)
                break;
        }
    }

    public function fallback() {
        $response = "Cannot find any songs matching " . implode(" ", $parameters) . ".";
    }

}
