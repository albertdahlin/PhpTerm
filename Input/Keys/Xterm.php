<?php

namespace Dahl\PhpTerm\Input\Keys;

/**
 * Xterm Key defenitions
 * 
 * @uses Basic
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com> 
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
class Xterm extends Basic
{
     const   HOME        = "\x1bOH",
             END         = "\x1bOF",
             BACKSPACE   = "\x7f",
             F1          = "\x1bOP",
             F2          = "\x1bOQ",
             F3          = "\x1bOR",
             F4          = "\x1bOS";
}
