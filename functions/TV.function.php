<?php

/**
 * begin remote

  name  TCL_TV
  begin codes
  KEY_POWER                0xF2A0D5
  KEY_VOLUMEUP             0xF2F0D0
  KEY_VOLUMEDOWN           0xF2E0D1
  KEY_MUTE                 0xF3F0C0
  KEY_MENU                 0xF9D062
  KEY_UP                   0xF590A6
  KEY_DOWN                 0xF580A7
  KEY_RIGHT                0xF570A8
  KEY_LEFT                 0xF560A9
  KEY_OK                   0xFF400B
  KEY_HOME                 0xF080F7
  KEY_BACK                 0xF060F9
  KEY_INFO                 0xFD102E
  KEY_INFO                 0xFD102E
  end codes

  end remote
 */
class TV extends UserFunctions {

    public $Name = "TV";
    public $Device = "TCL_TV";
    public $StateStorage = "TV";
    private $volume = array('min' => 0, 'max' => 100, 'default' => 15);
    public $triggered = false;
    
    protected $inputs = array(
        'PC',
        'High Definition 2',
        'High Definition 1',
        'Composite',
        'Video 2',
        'Video 1',
        'Analog TV',
    );
    public $primarytriggers = array(
        array('tv'),
        array('television'),
        array('screen'),
        array('display')
    );
    public $default_state = array(
        'power' => 0,
        'input' => 0,
        'volume' => 0,
        'mute' => 0,
        '3d' => 0
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
            array('toggle', 'turn', 'power', 'switch', '9c6651f707ba0cf8f8dcbe9329ff8cb8'),
        ),
        'setvolume' => array(
            array('set', 'volume', 'to'),
            array('adjust', 'volume', 'to'),
        ),
        'set_source_toggle' => array(
            array('set', 'pc'),
            array('set', 'vga'),
            array('set', 'computer'),
            array('set', 'hdmi', '1'),
            array('set', 'hdmi', 'one'),
            array('set', 'hdmi', '2'),
            array('set', 'hdmi', 'two'),
            array('set', 'composite'),
            array('set', 'av', '1'),
            array('set', 'for', 'cable'),
            array('set', 'for', 'game'),
            array('set', 'av', 'one'),
            array('set', 'video', '1'),
            array('set', 'video', 'one'),
            array('set', 'av', '2'),
            array('set', 'av', 'two'),
            array('set', 'video', '2'),
            array('set', 'video', 'two'),
            array('set', 'analog'),
            'btn_label' => 'Source',
            'btn_group' => 'tv sources',
            'btn_class' => 'with-label icon',
            array('source', 'toggle', 'control', 'auto', '7f794e63c61e2b6eb6e59a52b68f8f13')
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

        //Input Source
        $source = $this->triggers['set_source_toggle'];
        $return .= UserInterface::Button($source['btn_label'], $function_link . 'set_source_toggle', $source['btn_class']);

        return $return;
    }

    public function togglepower() {
        $state = $this->state->Get($this->StateStorage);
        $toggle = false;
        if (strpos($this->triggered['keywords'], '9c6651f707ba0cf8f8dcbe9329ff8cb8') !== FALSE) {
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
                TTS::Speak("Turning TV On");
            $state['power'] = 1;
        }
        elseif ((Prompt::MatchAnswer(array('off'), $this->triggered['keywords']) && $state['power'] == 1) || $toggle == 'OFF') {
            if ($this->speak)
                TTS::Speak("Turning TV Off");
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
            TTS::Speak("TV is currently off.");
        }
    }

    /**
     * protected $inputs = array(
      'PC',
      'HDMI2',
      'HDMI1',
      'YPBPR',
      'AV2',
      'AV1',
      'ATV',
      );
     * @return boolean
     */
    public function set_source_toggle() {
        $state = $this->state->Get($this->StateStorage);
        $current = $state['input'];
        $maxmode = (count($this->inputs));
        if (in_array("pc", $this->triggered['triggered']) || in_array("computer", $this->triggered['triggered']) || in_array("vga", $this->triggered['triggered'])) {
            $target = 0;
        } elseif (in_array("hdmi", $this->triggered['triggered'])) {
            if (in_array("two", $this->triggered['triggered']) || in_array("2", $this->triggered['triggered'])) {
                $target = 1;
            } else {
                $target = 2;
            }
        } elseif (in_array("for", $this->triggered['triggered'])) {
            if (in_array("game", $this->triggered['triggered'])) {
                $target = 1;
            } elseif(in_array("cable", $this->triggered['triggered'])) {
                $target = 2;
            }
        } elseif (in_array("composite", $this->triggered['triggered'])) {
            $target = 3;
        } elseif (in_array("av", $this->triggered['triggered']) || in_array("video", $this->triggered['triggered'])) {
            if (in_array("two", $this->triggered['triggered']) || in_array("2", $this->triggered['triggered'])) {
                $target = 4;
            } else {
                $target = 5;
            }
        } elseif (in_array("analog", $this->triggered['triggered'])) {
            $target = 6;
        } elseif (in_array("7f794e63c61e2b6eb6e59a52b68f8f13", $this->triggered['triggered'])) {
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

        if ($target > $current) {
            $function = "KEY_DOWN";
            $distance = $target - $current;
        } else {
            $function = "KEY_UP";
            $distance = $current - $target;
            //$distance += 1; //Add for zero
        }

        $runfunc = "sleep .5; irsend SEND_ONCE " . $this->Device . " KEY_MENU;";
        $runfunc .= "sleep .5; irsend SEND_ONCE " . $this->Device . " KEY_RIGHT;";
        $runfunc .= "sleep .5; irsend SEND_ONCE " . $this->Device . " KEY_UP;";
        /** Loop how many counts of distance to target * */
        for ($i = 1; $i <= $distance; $i++) {
            $runfunc .= "sleep .5; irsend SEND_ONCE " . $this->Device . " " . $function . ";";
        }
        $runfunc .= "sleep .5; irsend SEND_ONCE " . $this->Device . " KEY_OK;";
        $runfunc .= "sleep .5; irsend SEND_ONCE " . $this->Device . " KEY_BACK;";
        $runfunc .= "sleep .5; irsend SEND_ONCE " . $this->Device . " KEY_BACK;";
        $runfunc .= "sleep .5; irsend SEND_ONCE " . $this->Device . " KEY_BACK;";

        if ($this->speak)
            TTS::Speak('Setting TV source to: ' . $this->inputs[$target]);
        $state['input'] = $target;
        shell_exec($runfunc);
        $this->state->Set($this->StateStorage, $state);
    }
}
