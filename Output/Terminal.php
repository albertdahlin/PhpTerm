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

    public function hide()
    {
        echo "\x1b[?25l";

        return $this;
    }

    public function show()
    {
        echo "\x1b[?25h";

        return $this;
    }

    /**
     * Moves the cursor up by $amount cells.
     * 
     * @param int $amount
     * @access public
     * @return Terminal
     */
    public function up($amount = 1)
    {
        echo "\x1b[{$amount}A";

        return $this;
    }

    /**
     * Moves the cursor down by $amount cells.
     * 
     * @param int $amount
     * @access public
     * @return Terminal
     */
    public function down($amount = 1)
    {
        echo "\x1b[{$amount}B";

        return $this;
    }

    /**
     * Moves the cursor right by $amount cells.
     * 
     * @param int $amount
     * @access public
     * @return Terminal
     */
    public function right($amount = 1)
    {
        echo "\x1b[{$amount}C";

        return $this;
    }

    /**
     * Moves the cursor left by $amount cells.
     * 
     * @param int $amount
     * @access public
     * @return Terminal
     */
    public function left($amount = 1)
    {
        echo "\x1b[{$amount}D";

        return $this;
    }

    /**
     * Moves the cursor to the first row.
     * 
     * @access public
     * @return Terminal
     */
    public function top()
    {
        echo "\x1b[1000A";

        return $this;
    }

    /**
     * Moves the cursor to the last row.
     * 
     * @access public
     * @return Terminal
     */
    public function bottom()
    {
        echo "\x1b[1000B";

        return $this;
    }

    /**
     * Moves the cursor to the first column.
     * 
     * @access public
     * @return Terminal
     */
    public function home()
    {
        echo "\x1b[1000D";

        return $this;
    }

    /**
     * Moves the cursor to the first column.
     * 
     * @access public
     * @return Terminal
     */
    public function end()
    {
        echo "\x1b[1000C";

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
    public function setPos($row = null, $col = null)
    {
        if (is_array($row) && isset($row['row'], $row['col'])) {
            $col = $row['col'];
            $row = $row['row'];
        }
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
         $pos = $this->getPos();
         $this->setPos(1000, 1000);
         $size = $this->getPos();
         $this->setPos($pos);

         return $size;
    }

    /**
     * Returns the cursor position.
     * 
     * @access public
     * @return array
     */
    public function getPos()
    {
        $this->_clearInput();
        echo "\x1b[6n";
        $pos = $this->_getc();
        $pos = trim($pos, "R\x1b[");
        list($row, $col) = explode(';', $pos);

        return array(
            'row' => $row,
            'col' => $col
        );
    }

    /**
     * Clears any buffered bytes on STDIN.
     * 
     * @access protected
     * @return void
     */
    protected function _clearInput()
    {
        $read    = array(STDIN);
        $write   = NULL;
        $exclude = NULL;
        stream_select($read, $write, $exclude, 0);
        stream_set_blocking(STDIN, 0);
        stream_get_contents(STDIN, -1);
    }

    /**
     * Reads one char from STDIN. Will block until bytes are awailable.
     * 
     * @access protected
     * @return string
     */
    protected function _getc()
    {
        $read    = array(STDIN);
        $write   = NULL;
        $exclude = NULL;
        stream_select($read, $write, $exclude, null);
        stream_set_blocking(STDIN, 0);
        $char = stream_get_contents(STDIN, -1);

        return $char;
    }
}
