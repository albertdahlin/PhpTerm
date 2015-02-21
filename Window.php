<?php

namespace Dahl\PhpTerm;

/**
 * Wrapper class for handling input and output on a terminal application.
 * 
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com>
 * @license GNU GPL v3.0 <http://www.gnu.org/licenses/gpl-3.0.html>
 */
class Window
{
    /**
     * Input object.
     * 
     * @var Keyboard
     * @access protected
     */
    protected $_input;

    /**
     * Output class.
     * 
     * @var Terminal
     * @access protected
     */
    protected $_output;

    /**
     * Holds the window size.
     * 
     * @var array
     * @access protected
     */
    protected $_size;

    /**
     * The elements added to the window.
     * 
     * @var array
     * @access protected
     */
    protected $_elements = array();

    /**
     * Constructor. Initializes input and output.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_input  = new Input\Keyboard;
        $this->_output = new Output\Terminal;
    }

    /**
     * Returns the intput object.
     * 
     * @access public
     * @return Keyboard
     */
    public function getInput()
    {
        return $this->_input;
    }

    /**
     * Returns the output object.
     * 
     * @access public
     * @return Terminal
     */
    public function getOutput()
    {
        return $this->_output;
    }

    /**
     * Returns the widow size in rows and cols.
     * 
     * @access public
     * @return array
     */
    public function getSize()
    {
        if (!$this->_size) {
            $this->_size = $this->_output->getSize();
        }

        return $this->_size;
    }

    /**
     * Render window elements.
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        foreach ($this->_elements as $element) {
            $element->render();
        }
    }

    /**
     * Adds an element to the output and returns it.
     * 
     * @param string $id
     * @access public
     * @return Element
     */
    public function addElement($id)
    {
        if (!isset($this->_elements[$id])) {
            $element = new Output\Element($id);
            $this->_elements[$id] = $element;
            $element->setParent($this);

            return $element;
        } else {
            throw new Exception("An element with id {$id} already exists");
        }
    }
}
