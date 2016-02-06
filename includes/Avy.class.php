<?php
date_default_timezone_set('Asia/Manila');
define('FUNCTIONS_DIR',DOCUMENT_ROOT.'functions/');
define('INCLUDES_DIR',DOCUMENT_ROOT.'includes/');
define('MODULES_DIR',DOCUMENT_ROOT.'modules/');
define('TEMP_DIR',DOCUMENT_ROOT.'tmp/');

require_once INCLUDES_DIR.'Singleton.abstract.php';
require_once INCLUDES_DIR.'Session.class.php';
require_once INCLUDES_DIR.'States.class.php';
require_once INCLUDES_DIR.'UserFunctions.abstract.php';
require_once INCLUDES_DIR.'Triggers.class.php';
require_once INCLUDES_DIR.'Prompt.class.php';
require_once INCLUDES_DIR.'TTS.class.php';
require_once INCLUDES_DIR.'AvyCron.class.php';

class Avy
{
	public function Avy($parameters, $display_process = true)
	{

            $this->GetFunctionFiles();

            if($parameters != '__DASHBOARD__'){
            $parameters = strtolower($parameters);
            if(Prompt::Check())
            {
                Prompt::Process($parameters);
                return;
            }

            if($display_process) echo "Avy-process:'".$parameters."'\n";

            $Triggers = Triggers::getInstance();
            $findCommand = $Triggers->FindFunction($parameters);

            if(count($findCommand)>0) {
        	// Get top results
        	if(count($findCommand)==1){
        		foreach($findCommand as $key=>$data)
        		{
                            //var_dump($data);
                                $class = "";
                                eval('$class = new '.$data['class'].'();');
                                $function = $data['function'];
                                $class->setTriggered( array("keywords"=>$parameters, "trigger"=>$data) );
                                $class->$function();
        			//$class = call_user_func($data[0]."::". $data[1], array("keywords"=>$parameters, "trigger"=>$data));
        		}
        		//$class = call_user_func();
        	}else{

        		//Prompt which function to use
        		//Add to session
        	}
            }
            }
	}

	public function GetFunctionFiles($autoinclude = true)
	{
		// Loop thru functions and add the triggers to session
		foreach (new DirectoryIterator( FUNCTIONS_DIR ) as $fileInfo) {
		    if(!$fileInfo->isDot()){
		    	if($autoinclude){
		    		include_once(FUNCTIONS_DIR.$fileInfo->getFilename());
		    	}
				$classname = str_ireplace(".function.php", "", $fileInfo->getFilename());

				if(class_exists($classname)){
					$functionClass = new $classname( false );
					$Triggers = Triggers::getInstance();
					$Triggers->AddFunction($functionClass);
				}
		    }
		}
	}
}