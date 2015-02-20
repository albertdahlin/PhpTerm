<?php

namespace Dahl\PhpTerm\Output;

/**
 * Output handler for printing to terminal.
 * 
 * @package 
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com> 
 * @license GNU GPL v3.0 <http://www.gnu.org/licenses/gpl-3.0.html>
 */
class Terminal
{
    /**
     * Clears screen
     * 
     * @access public
     * @return Terminal
     */
    public function clear()
    {
        echo "\x1b2J";

        return $this;
    }
}
