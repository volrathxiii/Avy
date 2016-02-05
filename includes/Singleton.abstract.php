<?php
/**
 * Either using singleton or registry
 */
abstract class Singleton
{
    private static $_instances = array();

    public static function getInstance() {
        $class = get_called_class();
        if (!isset(self::$_instances[$class])) {
            $Obj = new $class();
            $Obj->_init(); // Initiate here
            self::$_instances[$class] = $Obj;

        }
        return self::$_instances[$class];
    }

    /**
     * initiate class
     */
    abstract protected function _init();

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone(){}
}