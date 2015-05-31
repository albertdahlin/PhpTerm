<?php

namespace Dahl\PhpTerm;

/**
 * Page Class.
 *
 * @uses Window
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com>
 * @license GNU GPL v3.0 <http://www.gnu.org/licenses/gpl-3.0.html>
 */
class Page extends Window
{
    /**
     * Window
     *
     * @var Window
     * @access protected
     */
    protected $_window;

    /**
     * Constructor
     *
     * @param Window $window
     * @access public
     * @return void
     */
    public function __construct($window)
    {
        $this->_window = $window;
        $this->_input  = $window->getInput();
        $this->_output = $window->getOutput();
    }

    /**
     * Render page.
     *
     * @param boolean $force
     * @access public
     * @return void
     */
    public function render($force = false)
    {
        $this->_output->cls();

        return parent::render($force);
    }
}
