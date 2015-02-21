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
     * Clear screen
     * 
     * @access public
     * @return Terminal
     */
    public function cls()
    {
        echo "\x1b[2J";

        return $this;
    }

    /**
     * Sets the cursor position. Defaults to top left corner.
     * 
     * @param int $row
     * @param int $col
     * @access public
     * @return Terminal
     */
    public function pos($row = null, $col = null)
    {
        if (!(int)$row) {
            $row = 1;
        }
        if (!(int)$col) {
            $col = 1;
        }
        echo "\x1b[{$row};{$col}H";

        return $this;
    }

    /**
     * Returns viewport size.
     * 
     * @access public
     * @return array
     */
    public function getSize()
    {
    }

    /**
     * Returns the cursor position.
     * 
     * @access public
     * @return array
     */
    public function getPos()
    {
        echo "\x1b[6n";
    }
}
