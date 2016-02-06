<?php

class States extends Singleton {

    protected $states = false;

    public function _init() {
        $session = Session::getInstance();
        $this->states = $session->get('_STATES_');
    }

    public function Get($class) {
        $this->_init(); // Rerun fetch states
        if ($this->states[$class]) {
            return $this->states[$class];
        }
    }

    public function Set($class, $data) {
        $this->states[$class] = $data;
        $session = Session::getInstance();
        $session->set('_STATES_', $this->states);
    }

}
