<?php

abstract class UserFunctions {

    public $Name = "";
    public $speak = true;
    public $triggers = false;
    public $triggered = false;
    public $default_state = false;
    public $state = false;
    public $primarytriggers;
    protected $current_date = false;

    public function __construct() {
        if (!$this->triggers) {
            echo "ERROR: No triggers defined for '" . strtoupper(get_called_class()) . "'.\n";
            return false;
        }
        $this->initialize();
        $this->current_date = getdate();
    }

    public function initialize() {
        $state = States::getInstance();
        $currentState = $state->Get(get_called_class());
        if (!is_array($currentState)) {
            $state->Set(get_called_class(), $this->default_state);
        }
        $this->state = $state;
    }

    public function dashboard() {
        return false;
    }
    
    public function removeKeywords($filltered_keywords, $keywords = false){
        if(!$keywords){
            $keywords = $this->triggered['filtered'];
        }
        
        if(is_array($this->primarytriggers))
        {
            foreach($this->primarytriggers as $key=>$filterremove){
                foreach($filterremove as $id=>$removeword){
                    if($findkey = array_search($removeword, $filltered_keywords)){
                        unset($filltered_keywords[$findkey]);
                    }
                }
            }
        }
        return $filltered_keywords;
    }

    public function wordsToNumber($data) {
        // Replace all number words with an equivalent numeric value
        $data = strtr(
                $data, array(
            'zero' => '0',
            'a' => '1',
            'one' => '1',
            'two' => '2',
            'three' => '3',
            'four' => '4',
            'five' => '5',
            'six' => '6',
            'seven' => '7',
            'eight' => '8',
            'nine' => '9',
            'ten' => '10',
            'eleven' => '11',
            'twelve' => '12',
            'thirteen' => '13',
            'fourteen' => '14',
            'fifteen' => '15',
            'sixteen' => '16',
            'seventeen' => '17',
            'eighteen' => '18',
            'nineteen' => '19',
            'twenty' => '20',
            'thirty' => '30',
            'forty' => '40',
            'fourty' => '40', // common misspelling
            'fifty' => '50',
            'sixty' => '60',
            'seventy' => '70',
            'eighty' => '80',
            'ninety' => '90',
            'hundred' => '100',
            'thousand' => '1000',
            'million' => '1000000',
            'billion' => '1000000000',
            'and' => '',
                )
        );

        // Coerce all tokens to numbers
        $parts = array_map(
                function ($val) {
            return floatval($val);
        }, preg_split('/[\s-]+/', $data)
        );

        $stack = new SplStack; // Current work stack
        $sum = 0; // Running total
        $last = null;

        foreach ($parts as $part) {
            if (!$stack->isEmpty()) {
                // We're part way through a phrase
                if ($stack->top() > $part) {
                    // Decreasing step, e.g. from hundreds to ones
                    if ($last >= 1000) {
                        // If we drop from more than 1000 then we've finished the phrase
                        $sum += $stack->pop();
                        // This is the first element of a new phrase
                        $stack->push($part);
                    } else {
                        // Drop down from less than 1000, just addition
                        // e.g. "seventy one" -> "70 1" -> "70 + 1"
                        $stack->push($stack->pop() + $part);
                    }
                } else {
                    // Increasing step, e.g ones to hundreds
                    $stack->push($stack->pop() * $part);
                }
            } else {
                // This is the first element of a new phrase
                $stack->push($part);
            }

            // Store the last processed part
            $last = $part;
        }

        return $sum + $stack->pop();
    }

    public function getFunctionByLabel($label) {
        foreach ($this->triggers as $function_name => $function) {
            foreach ($function as $key => $value) {
                if ($key == 'btn_label' && $value == $label) {
                    $return = $function_name;
                }
            }
        }
        return $return;
    }

    public function getTriggersByGroup($group) {
        $return = array();
        foreach ($this->triggers as $function_name => $function) {
            foreach ($function as $key => $value) {
                if ($key == 'btn_group' && $value == $group) {
                    $return[$function_name] = $function;
                }
            }
        }
        return $return;
    }

    public function setTriggered($parameters) {
        $this->initialize();
        if (isset($parameters['trigger'])) {
            $this->triggered['keywords'] = $parameters['keywords'];
            $this->triggered['key'] = $parameters['trigger']['trigger'];
            $this->triggered['triggered'] = $this->triggers[$parameters['trigger']['function']][$this->triggered['key']];
            $this->triggered['filtered'] = $this->filterKeywords($this->triggered['triggered'], $this->triggered['keywords']);
        }
    }

    public function filterKeywords(array $triggered, $keywords) {
        foreach ($triggered as $find_word) {
            $pos = strpos($keywords, $find_word);
            if ($pos !== false) {
                $keywords = substr_replace($keywords, '', $pos, strlen($find_word));
            }
        }
        //var_dump($keywords);
        $return = explode(" ", $keywords);
        return (array_filter($return));
    }

}
