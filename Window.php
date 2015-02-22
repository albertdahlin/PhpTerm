<?php

namespace Dahl\PhpTerm;

/**
 * Wrapper class for handling input and output on a terminal application.
 * 
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
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
     * An element that has focus.
     * 
     * @var mixed
     * @access protected
     */
    protected $_focus;

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
     * Set focus on an element.
     * 
     * @param Element $element
     * @access public
     * @return Window
     */
    public function setFocus($element)
    {
        $this->_focus = $element;

        return $this;
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

        if ($this->_focus) {
            $this->_focus->applyFocus();
        }
    }

    /**
     * Adds an element to the output and returns it.
     * 
     * @param string $id
     * @access public
     * @return Element
     */
    public function addElement($id, $type = 'Element')
    {
        if (!isset($this->_elements[$id])) {
            $type = __namespace__ . '\\Output\\' . ucfirst($type);
            $element = new $type($id);
            $this->_elements[$id] = $element;
            $element->setParent($this);

            return $element;
        } else {
            throw new Exception("An element with id {$id} already exists");
        }
    }
}
