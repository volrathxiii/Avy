<?php

class Triggers extends Singleton {

    protected $functions;
    protected $classes;
    private $possibleCommands = false;

    public function _init() {
        
    }
    
    public function GetClasses()
    {
        return $this->classes;
    }
    
    public function GetFunctions()
    {
        return $this->functions;
    }

    public function AddFunction(UserFunctions $object) {
        $classname = get_class($object);
        $this->functions[$classname] = $object->triggers;
        $this->classes[$classname] = $object->primarytriggers;
    }
    /**
     * @todo add insanity checks
     * @param type $class
     * @param type $index
     * @return type
     */
    public function GetClassTrigger($class, $index="")
    {
        if(is_int($index)){
            return $this->classes[$class][$index];
        }else{
            $topcount = 0;
            $topindex = 0;
            foreach($this->classes[$class] as $returnindex => $classtriggers)
            {
                if($topcount < count($classtriggers))
                {
                    $topcount = count($classtriggers);
                    $topindex = $returnindex;
                }
            }
            return $topindex;
        }
    }
    /**
     * @todo add insanity checks
     * @param type $class
     * @param type $function
     * @param type $index
     * @return type
     */
    public function GetFunctionTrigger($class, $function, $index="")
    {
        if(is_int($index)){
            return $this->functions[$class][$function][$index];
        }else{
            $topcount = 0;
            $topindex = 0;
            foreach($this->functions[$class][$function] as $returnindex => $triggers)
            {
                if($topcount < count($triggers))
                {
                    $topcount = count($triggers);
                    $topindex = $returnindex;
                }
            }
            return $topindex;
        }
    }

    /**
     * @todo add weight
     * @param unknown_type $parameters
     */
    public function FindFunction($parameters) {
        $result = array();
        $return = array();
        $allcommands = array();
        $params = explode(" ", $parameters);

        foreach ($this->functions as $CLASS => $FUNCTIONS) {
            foreach ($FUNCTIONS as $FUNCTION => $SETS) {
                foreach ($SETS as $SET => $KEYWORDS) {
                    $SETID = 0;
                    if (is_int($SET)) {
                        foreach ($this->classes[$CLASS] as $primaries => $primary) {
                            //Append primarry triggers
                            //var_dump($primary);
                            $SETID += 1;
                            $KEYWORDS_MERGED = array_merge($KEYWORDS, $primary);

                            foreach ($KEYWORDS_MERGED as $KEYWORD) {

                                if (in_array($KEYWORD, $params)) {
                                    if (isset($result[$CLASS . "::" . $FUNCTION . "::" . $SET . "::" . $SETID])) {
                                        $result[$CLASS . "::" . $FUNCTION . "::" . $SET . "::" . $SETID] += 1;
                                    } else {
                                        $result[$CLASS . "::" . $FUNCTION . "::" . $SET . "::" . $SETID] = 1;
                                    }
                                    $keywordcount = count($KEYWORDS_MERGED);
                                    $matchcount = $result[$CLASS . "::" . $FUNCTION . "::" . $SET . "::" . $SETID];
                                    $matchpercent = ($matchcount / $keywordcount) * 50;
                                    $filterpercent = ($matchcount / count($params)) * 50;
                                    //echo $filterpercent."\n";
                                    $return[$CLASS . "::" . $FUNCTION . "::" . $SET . "::" . $SETID] = $matchpercent + $filterpercent;
                                    $returnmatch[$CLASS . "::" . $FUNCTION . "::" . $SET . "::" . $SETID] = $matchpercent * 2;
                                    arsort($return);
                                }
                            }
                        }
                    }
                }
            }
        }
        //var_dump($result);
        //fix result
        $key = 0;
        foreach ($return as $key => $percent) {
            list($classname, $functionname, $triggerkey, $setid) = explode("::", $key);
            $allcommands[$key]['class'] = $classname;
            $allcommands[$key]['function'] = $functionname;
            $allcommands[$key]['trigger'] = $triggerkey;
            $allcommands[$key]['overall'] = $percent;
            $allcommands[$key]['matches'] = $returnmatch[$classname . "::" . $functionname . "::" . $triggerkey . "::" . $setid];
            $key += 1;
        }

        if (count($allcommands) > 0) {
            // Get top result
            // Top result might return 2 or more commands
            $commandPercent = 0;
            $topcommand = array();
            foreach ($allcommands as $key => $data) {
                if ($commandPercent == 0 && $data['overall'] > 50)
                    $commandPercent = $data['overall'];

                if ($commandPercent == $data['overall']) {
                    $topcommand[$data['class'] . "::" . $data['function']] = $data;
                }
            }
            return $topcommand;
        }
    }

}
