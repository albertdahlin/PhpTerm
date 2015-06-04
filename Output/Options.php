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
    protected $_options = array();
    protected $_focused;
    protected $_width;
    protected $_index;

    public function setOptions($options)
    {
        $this->_options = array();
        $width          = 0;

        foreach ($options as $key => $value) {
            if (is_array($value)) {
                if (isset($value['label'], $value['value'])) {
                    $value['key']     = $key;
                    $this->_options[] = $value;
                }
                $width = max($width, mb_strlen($value['label']));
            } else {
                $this->_options[] = array(
                    'key'   => $key,
                    'value' => $key,
                    'label' => $value,
                );
                $width = max($width, mb_strlen($value));
            }
        }

        $this->_width = $width;
        $this->_text  = array();

        return $this;
    }

    public function setFocused($key)
    {
        $this->_focused = $key;

        return $this;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    protected function _getIndex()
    {
        $index = 0;
        $this->_index = 0;
        if ($this->_focused) {
            foreach ($this->_options as $idx => $option) {
                if ($option['key'] === $this->_focused) {
                    $this->_index = $idx;
                    return $idx;
                }
            }
        }

        return 0;
    }

    public function getSelected()
    {
        $index      = $this->_getIndex();
        $max        = count($this->_options) - 1;
        $size       = $this->getSize();
        $startRow   = $this->_getStartRow();
        $maxHeight  = min($size['row'] - $startRow, $this->getMaxHeight(), $max + 1);
        $width      = $this->getWidth();
        $start      = $this->_getStart($index, $max, $maxHeight);
        $input      = $this->getInput();
        $key        = $input->getKeys();
        $strPad     = $this->_getStrPad();

        while (true) {
            $this->_text        = array();
            $this->_hasChanges  = true;
            $this->_focused     = null;
            $stop               = $maxHeight + $start;

            for ($i = $start; $i < $stop; $i++) {
                $option = $this->_options[$i];
                $label = $this->_prepareLabel(
                    str_pad($option['label'], $width, ' ', $strPad),
                    $i
                );
                if ($index == $i) {
                    $this->_focused = $option['key'];
                    $this->_text[] = array(
                        'color' => 'black',
                        'background' => 'light_grey',
                        'text'  => $label,
                    );
                } else {
                    $this->_text[] = array('text' => $label);
                }
            }

            parent::render();
            $this->applyFocus($index - $start);

            $char  = $input->readChar();

            switch ($char) {
                case 'j':
                case $key::DOWN:
                    $index = min($index + 1, $max);
                    break;

                case 'k':
                case $key::UP:
                    $index = max($index - 1, 0);
                    break;

                case 'g':
                case $key::HOME:
                    $index = 0;
                    break;

                case 'G':
                case $key::END:
                    $index = $max;
                    break;

                case 'J':
                case $key::PGDOWN:
                case $key::CTRL_F:
                    $index = min($index + $maxHeight, $max);
                    break;

                case 'K':
                case $key::CTRL_B:
                case $key::PGUP:
                    $index = max($index - $maxHeight, 0);
                    break;

                case $key::CTRL_D:
                    $index = min($index + floor($maxHeight / 2), $max);
                    break;

                case $key::CTRL_U:
                    $index = max($index - floor($maxHeight / 2), 0);
                    break;

                default:
                    $return = $this->_keyEvent($char);

                    if ($return !== null) {
                        return $return;
                    }
            }
            $this->_index   = $index;
            $start          = $this->_getStart($index, $max, $maxHeight, $start);
        }
    }

    public function applyFocus($rowOffset = 0)
    {
        $output = $this->getOutput();
        $width  = $this->getWidth();
        $height = $this->getHeight();

        $row = $this->_getStartRow($height) + $rowOffset;
        $col = $this->_getStartCol($width, $width);
        $output->setPos($row, $col);
    }

    protected function _getStrPad()
    {
        $style = $this->getStyle('text-align');
        switch ($style) {
            case 'center':
                return STR_PAD_BOTH;
            case 'left':
                return STR_PAD_LEFT;
            default:
                return STR_PAD_RIGHT;
        }
    }

    protected function _getStart($index, $max, $maxHeight, $start = null)
    {
        if ($start !== null) {
            if ($index >= ($start + $maxHeight - 1)) {
                return  max(
                    min(
                        $index - $maxHeight + 2,
                        $max - $maxHeight + 1
                    ),
                    0
                );
            }

            if ($index <= $start) {
                return  max(
                    min(
                        $index - 1,
                        $max - $maxHeight + 1
                    ),
                    0
                );
            }

            return $start;
        } else {
            return  max(
                min(
                    $index - floor($maxHeight / 2),
                    $max - $maxHeight + 1
                ),
                0
            );
        }
    }

    protected function _prepareLabel($label, $i)
    {
        return $label;
    }

    protected function _keyEvent($char)
    {
        $key = $this->getInput()->getKeys();
        switch ($char) {
            case $key::ENTER:
                return $this->_focused;

            case $key::ESC:
                return false;
        }

        return null;
    }
}
