<?php

namespace Dahl\PhpTerm\Output;

/**
 *
 *
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
class Options
 extends Element
{
    protected $_options;
    protected $_selected;

    public function setOptions($options)
    {
        $this->_options = $options;
    }

    public function getOption()
    {
        $index = 0;
        $max = count($this->_options) - 1;
        $size = $this->getSize();
        $startRow = $this->_getStartRow();
        $maxHeight = $size['row'] - $startRow;
        $start  = 0;

        while (true) {
            $this->_text = array();
            $this->_hasChanges  = true;
            $this->_selected    = null;
            $i = -1;
            $j = 0;

            foreach ($this->_options as $value => $label) {
                $i++;
                if ($start > $i) {
                    continue;
                }
                if (($maxHeight) <= $j++) {
                    break;
                }
                if ($index == $i) {
                    $this->_selected = $value;
                }
                if ($this->_selected == $value) {
                    $this->_text[] = array(
                        'color' => 'black',
                        'background' => 'light_grey',
                        'text'  => " {$label} ",
                    );
                } else {
                    $this->_text[] = array('text' => " {$label} ");
                }
            }

            parent::render();
            $this->applyFocus($index - $start);

            $input = $this->getInput();
            $key   = $input->getKeys();
            $char  = $input->readChar(
                'jkJKGg',
                array(
                    $key::ESC,
                    $key::ENTER,
                    $key::UP,
                    $key::DOWN,
                    $key::END,
                    $key::HOME,
                    $key::PGUP,
                    $key::PGDOWN,
                    $key::CTRL_B,
                    $key::CTRL_F,
                )
            );

            switch ($char) {
                case 'j':
                case $key::DOWN:
                    $index = min($index + 1, $max);
                    if ($start + $maxHeight <= $index) {
                        $start++;
                    }
                    break;

                case 'k':
                case $key::UP:
                    $index = max($index - 1, 0);
                    if ($start > $index) {
                        $start--;
                    }
                    break;

                case 'g':
                case $key::HOME:
                    $index = 0;
                    $start = 0;
                    break;

                case 'G':
                case $key::END:
                    $index = $max;
                    $start = $max - $maxHeight + 1;
                    break;

                case 'J':
                case $key::PGDOWN:
                case $key::CTRL_F:
                    $index = min($index + $maxHeight, $max);
                    $start = max($index - $maxHeight + 1, 0);
                    break;

                case 'K':
                case $key::CTRL_B:
                case $key::PGUP:
                    $index = max($index - $maxHeight, 0);
                    $start = $index;
                    break;

                case $key::ENTER:
                    return $this->_selected;
                case $key::ESC:
                    return;
            }
        }
    }

    public function applyFocus($rowOffset = 0)
    {
        $output = $this->getOutput();
        $width  = $this->getWidth();
        $height = $this->getHeight();

        $row = $this->_getStartRow($height) + $rowOffset;
        $col = $this->_getStartCol($width, $width);
        $output->setPos($row, $col + 1);
    }
}
