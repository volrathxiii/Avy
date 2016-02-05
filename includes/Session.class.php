<?php

class Session extends Singleton
{
	protected $data;
	private $session_file = 'sessions.dat';

	public function _init()
	{
		$dat = file_get_contents( TEMP_DIR . $this->session_file );
		$this->data = unserialize($dat);
	}
        
        public function get($name)
        {
            if(isset($this->data[$name])){
                return $this->data[$name];
            }
        }
        
        public function set($name, $value){
            if(isset($name)){
                $this->data[$name] = $value;
                $this->save();
            }
        }

	protected function save()
	{
		$dat = serialize($this->data);
		file_put_contents( TEMP_DIR . $this->session_file, $dat );
	}
}