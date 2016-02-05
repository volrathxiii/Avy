<?php

class TTS
{
    static public function Speak($phrases)
    {
        if(!is_null($phrases))
        {
            if(is_array($phrases))
            {
                $speak = $phrases[array_rand($phrases, 1)];
            }else{
                $speak = $phrases;
            }
            //Change to tts command
            $res = preg_replace("/[^a-zA-Z0-9.@:\s]/", "", $speak);
            echo $res."\n";
            shell_exec('sudo tts '.str_replace(array('"',"'",'!'), "", $res));
        }
    }
}
