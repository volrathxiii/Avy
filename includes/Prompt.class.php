<?php
class Prompt
{
    public static $cancel_keywords = array(
        array('cancel','prompt'),
        array('stop','prompt'),
    );
    
    public static $Yes = array(
        "yes",
        "okay",
        "fine",
        "sure",
        "ofcourse",
        "of course",
    );
    
    public static $No = array(
        "no",
        "nope",
        "nah",
    );
    
    static function MatchAnswer(array $possible_answers, $answer)
    {
        foreach($possible_answers as $matchlookup){
            if (strpos($answer, $matchlookup) !== FALSE){
                return true;
            }
        }
        
        return false;
    }
    
    static function Set($question, $object, $function)
    {
        if($question && $object && $function){
            
            TTS::Speak($question);
            $prompt_session = array(
                'question'=>$question,
                'class'=>$object,
                'function'=>$function,
                'expiry'=>time()+60
            );
            $session = Session::getInstance();
            $session->set('_PROMPT_',$prompt_session);
            //Set AV to listen mode
            echo "Setting Avy to Listen Mode!\n";
        }
    }
    
    static function Check()
    {
        $session = Session::getInstance();
        $prompt_session = $session->get('_PROMPT_');
        if(is_array($prompt_session)){
            if(time() >= $prompt_session['expiry'])
            {
                echo "Prompt Expired: ".$prompt_session['question']."\n";
                self::Clear();
                return false;
            }
            return true;
        }
        
        return false;
    }
    
    static function Get()
    {
        $session = Session::getInstance();
        $prompt_session = $session->get('_PROMPT_');
        if(is_array($prompt_session)){
            return $prompt_session;
        }
        return false;
    }
    
    // This will check if prompt has been set.
    static function Process($message)
    {
        $message_words = explode(" ",$message);
        
        $session = Session::getInstance();
        $prompt_session = $session->get('_PROMPT_');
        
        if(is_array($prompt_session))
        {
            // Check if cancel Prompt
            echo "System Prompt: ".$prompt_session['question']."\n";
            echo "Answered: ".$message."\n";
            $search = array();
            foreach(self::$cancel_keywords as $key => $sentences)
            {
                $search[$key]=0;
                foreach($sentences as $word){
                    if(in_array($word, $message_words))
                    {
                        $search[$key] += 1;
                    }
                }   
                
                if($search[$key] >= count($sentences)){
                    //Cancel Prompt
                    TTS::Speak('Okay, i wont ask anymore!');
                    self::Clear();
                    return;
                }
            }
            
            // Forward prompt to Class::Method
            $class = "";
            eval('$class = new '.$prompt_session['class'].'();');
            $function = $prompt_session['function'];
            //$class->setTriggered( array("keywords"=>$parameters, "trigger"=>$data) );
            $class->$function($message);
        }
    }
    
    static function Clear()
    {
        $session = Session::getInstance();
        $session->set('_PROMPT_',false);
        echo "Setting Avy to Stop Listening!\n";
        // Set Avy to Listening mode
    }
}
