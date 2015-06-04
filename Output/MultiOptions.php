<?php

namespace Dahl\PhpTerm\Output;

class MultiOptions
 extends Options
{
    protected $_selected = array();

    protected function _prepareLabel($label, $i)
    {
        if (isset($this->_selected[$i])) {
            $isSelected = 'X';
        } else {
            $isSelected = ' ';
        }
        return "[{$isSelected}] $label";
    }

    protected function _keyEvent($char)
    {
        $key = $this->getInput()->getKeys();
        switch ($char) {
            case $key::ENTER:
                $options = array();
                foreach ($this->_options as $key => $option) {
                    if (isset($this->_selected[$key])) {
                        $options[$option['key']] = $option['value'];
                    }
                }
                return $options;

            case $key::SPACE:
                if (isset($this->_selected[$this->_index])) {
                    unset($this->_selected[$this->_index]);
                } else {
                    $this->_selected[$this->_index] = 1;
                }

                return null;

            case $key::ESC:
                return false;
        }

        return null;
    }

    public function applyFocus($rowOffset = 0)
    {
        $output = $this->getOutput();
        $width  = $this->getWidth();
        $height = $this->getHeight();

        $row = $this->_getStartRow($height) + $rowOffset;
        $col = $this->_getStartCol($width, $width);
        $output->setPos($row, $col - 1);
    }
}
