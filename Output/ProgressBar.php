<?php

namespace Dahl\PhpTerm\Output;

/**
 * Prints a progress bar.
 * 
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com> 
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
class ProgressBar
 extends Element
{
    /**
     * Progress bar start value;
     * 
     * @var float
     * @access protected
     */
    protected $_min = 0;

    /**
     * Progress bar end value.
     * 
     * @var float
     * @access protected
     */
    protected $_max = 100;

    /**
     * Progress bar current value.
     * 
     * @var float
     * @access protected
     */
    protected $_pos = 0;

    /**
     * Progress bar with in columns.
     * 
     * @var float
     * @access protected
     */
    protected $_width = 50;

    /**
     * If the output should use UTF-8 chars or ascii.
     * 
     * @var boolen
     * @access protected
     */
    protected $_isUTF = true;

    /**
     * Set the progress bar start value
     * 
     * @param float $min
     * @access public
     * @return void
     */
    public function setMin($min = 0)
    {
        $this->_min = $min;

        return $this;
    }

    /**
     * Set the progress bar end value.
     * 
     * @param float $max
     * @access public
     * @return void
     */
    public function setMax($max = 100)
    {
        $this->_max = $max;

        return $this;
    }

    /**
     * Set the progress bar width in columns. Also
     * accepts a percentage of the screen width, eg 100%.
     * 
     * @param int $width
     * @access public
     * @return void
     */
    public function setWidth($width = 10)
    {
        if (substr($width, -1) == '%') {
            $size = $this->getSize();
            $width = substr($width, 0, -1) / 100;
            $width = ceil($size['col'] * $width);
        }
        $this->_width = $width;

        return $this;
    }

    /**
     * Set the progress bar current position.
     * 
     * @param float $pos
     * @access public
     * @return void
     */
    public function setPosition($pos = 0)
    {
        $this->_hasChanges = true;
        $this->_pos = $pos;

        return $this;
    }

    /**
     * Enable or disable UTF chars.
     * 
     * @param mixed $flag
     * @access public
     * @return void
     */
    public function setUtf($flag = true)
    {
        $this->_isUTF = $flag;

        return $this;
    }

    /**
     * Render the progress bar.
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        if (!$this->_hasChanges) {
            return;
        }
        $width = $this->_width;
        $output = $this->getOutput();
        $col   = $this->_getStartCol($width, $width);
        $row   = $this->_getStartRow();
        $charE = $this->_isUTF ? '░' : '-';
        $charF = $this->_isUTF ? '▊' : '#';
        $min   = $this->_min;
        $minL  = strlen($min);
        $max   = $this->_max;
        $maxL  = strlen($max);
        $val   = max(min($this->_pos, $max), $min);
        $barL  = $width - 9 - $minL - $maxL * 2;
        $part = ($val - $min) / ($max - $min);
        $pos   = floor($part * $barL);
        $percent = str_pad(floor($part * 100), 4, ' ', STR_PAD_LEFT);
        $bar   = str_repeat($charF, $pos) . str_repeat($charE, $barL - $pos);
        $val   = str_pad($this->_pos, $maxL, ' ', STR_PAD_LEFT);
        $text  = "{$min} [{$bar}] {$val}/{$max}{$percent}%";
        $output->setPos($row, $col);
        echo $text;
        $this->_hasChanges = false;
    }
}
