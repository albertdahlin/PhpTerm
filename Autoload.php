<?php
namespace Term;

/**
 * Autoloader for terminal io lib.
 * 
 * @package 
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com>
 * @license GNU GPL v3.0 <http://www.gnu.org/licenses/gpl-3.0.html>
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
        spl_autoload_register(array('Term\Autoload', 'autoload'));
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
        if (substr($class, 0, 5) == 'Term\\') {
            include str_replace('\\', '/', substr($class, 5)) . '.php';
            return true;
        }

        return false;
    }
}

Autoload::register();
