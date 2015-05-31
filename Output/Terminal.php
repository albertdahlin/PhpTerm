<?php

namespace Dahl\PhpTerm\Output;

/**
 * Output handler for printing to terminal.
 *
 * @package
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
class Terminal
{
    /**
     * Ansi escape codes for foreground colors.
     *
     * @var array
     * @access protected
     */
    protected $_fgColors = array(
        'black'         => '30',
        'grey'          => '30;1',
        'red'           => '31',
        'light_red'     => '31;1',
        'green'         => '32',
        'light_green'   => '32;1',
        'yellow'        => '33',
        'brown'         => '33;1',
        'blue'          => '34',
        'light_blue'    => '34;1',
        'purple'        => '35',
        'light_purple'  => '35;1',
        'cyan'          => '36',
        'light_cyan'    => '36;1',
        'light_grey'    => '37',
        'white'         => '37;1',
    );

    /**
     * Ansi escape codes for background colors.
     *
     * @var array
     * @access protected
     */
    protected $_bgColors = array(
        'black'         => '40',
        'grey'          => '40;1',
        'red'           => '41',
        'light_red'     => '41;1',
        'green'         => '42',
        'light_green'   => '42;1',
        'yellow'        => '43',
        'brown'         => '43;1',
        'blue'          => '44',
        'light_blue'    => '44;1',
        'purple'        => '45',
        'light_purple'  => '45;1',
        'cyan'          => '46',
        'light_cyan'    => '46;1',
        'light_grey'    => '47',
        'white'         => '47;1',
    );
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
     * Set column position.
     *
     * @param int $col
     * @access public
     * @return Terminal
     */
    public function setCol($col = 1)
    {
        echo "\x1b[{$col}G";

        return $this;
    }

    /**
     * Returns an ANSI foreground color number.
     *
     * @param string $color
     * @access public
     * @return string
     */
    public function getFgColor($color)
    {
        if (isset($this->_fgColors[$color])) {
            return $this->_fgColors[$color];
        }
    }

    /**
     * Returns an ANSI background color number.
     *
     * @param string $color
     * @access public
     * @return string
     */
    public function getBgColor($color)
    {
        if (isset($this->_bgColors[$color])) {
            return $this->_bgColors[$color];
        }
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
