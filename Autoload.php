<?php
namespace Dahl\PhpTerm;

/**
 * Autoloader for terminal io lib.
 * 
 * @package 
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
class Autoload
{
    /**
     * Register spl autoload function.
     * 
     * @static
     * @access public
     * @return void
     */
    static public function register()
    {
        spl_autoload_register(array('Dahl\\PhpTerm\\Autoload', 'autoload'), true, true);
    }

    /**
     * Autoload function.
     * 
     * @param string $class
     * @static
     * @access public
     * @return boolean
     */
    static public function autoload($class)
    {
        $prefix = 'Dahl\\PhpTerm\\';
        $len    = strlen($prefix);
        if (substr($class, 0, $len) == $prefix) {
            $filename = substr($class, $len);
            $filename = str_replace('\\', DIRECTORY_SEPARATOR, $filename);
            $filename .= '.php';
            include $filename;

            return true;
        }

        return false;
    }
}

Autoload::register();
