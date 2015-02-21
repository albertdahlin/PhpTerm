<?php

namespace Dahl\PhpTerm\Input;

/**
 * Reads keyboard input from stdin.
 * 
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com> 
 * @license GNU GPL v3.0 <http://www.gnu.org/licenses/gpl-3.0.html>
 */
class Keyboard
{
    /**
     * Stores tty config.
     * 
     * @var string
     * @access protected
     */
    protected $_termConfig;

    /**
     * Holds a mapping class for escape codes to keys.
     * 
     * @var mixed
     * @access Base
     */
    protected $_keys;

    /**
     * Holds an autocomplete callback.
     * 
     * @var callable
     * @access protected
     */
    protected $_autoComplete;

    /**
     * Class constructor.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_termConfig = exec('stty -g');
        system('stty -icanon -echo -isig iutf8');
        $this->_keys = new Keys\Xterm;
    }

    /**
     * Class destructor.
     * 
     * @access public
     * @return void
     */
    public function __destruct()
    {
        system("stty {$this->_termConfig}");
    }

    /**
     * Returns key class map.
     * 
     * @access public
     * @return void
     */
    public function getKeys()
    {
        return $this->_keys;
    }

    /**
     * Reads stdin in non-blocking mode.
     * 
     * @access public
     * @return string;
     */
    public function readInput()
    {
        $read    = array(STDIN);
        $write   = NULL;
        $exclude = NULL;
        stream_select($read, $write, $exclude, 0);
        stream_set_blocking(STDIN, 0);

        return stream_get_contents(STDIN, -1);
    }

    /**
     * Blocks execution and reads one char from the keyboard. Only returs when
     * a key matching the masks are pressed.
     *
     * @param string $charset A charset to match. Accepts regex charsets, eg
     *                        \d for number, a-z for a to z, \S for all non whitespace etc.
     * @param array  $keys    An array of key identifiers. Use class constants from Keys\Base.
     *                        Useful when you want to read arrow keys, pgup etc.
     * @access public
     * @return string
     */
    public function readChar($charset = null, array $keys = null)
    {
        $this->_clear();
        while (true) {
            $char = $this->_getc();

            if (!$charset && !$keys) {
                break;
            }

            if (is_array($keys) && in_array($char, $keys)) {
                break;
            }

            if (is_string($charset) && preg_match("/^[{$charset}]$/u", $char)) {
                break;
            }
        }

        return $char;
    }

    /**
     * Registers an autocomplete callback. The callback should accept one string argument,
     * the input so far. The callback should return an array of options.
     * 
     * @param callable $callback    function($input)
     * @access public
     * @return void
     */
    public function registerAutocomplete($callback)
    {
        if (is_callable($callback)) {
            $this->_autoComplete = $callback;
        }
    }

    /**
     * Reads one line from the keyboard. Will accept all printable characters.
     * You can set a mask of which characters to accept.
     *
     * @param string    $prompt The prompt to display.
     * @param char|bool $echo   True | False if you want characters to be printed as typed
     *                          on the keyboard. You can also pass a character that should be
     *                          echoed instead, useful for password input, eg "*"
     * @param string    $mask   A mask of which characters to accept. Accepts any valid regex
     *                          charset, eg "a-zA-Z", "\d", "\p{L}", "[:print:] etc.
     * @access public
     * @return string
     */
    public function readLine($prompt = ': ', $echo = true, $mask = null)
    {
        $input  = array();
        $key    = $this->getKeys();
        $char   = null;
        $cursor = 0;
        if (!$mask) {
            $mask = '\p{L}[:print:]';
        }

        echo $prompt;
        while (true) {
            $char = $this->readChar(
                $mask,
                array(
                    $key::ENTER,
                    $key::BACKSPACE,
                    $key::TAB,
                )
            );
            switch ($char) {
                case $key::ENTER:
                    break 2;

                case $key::BACKSPACE:
                    if ($cursor > 0) {
                        array_pop($input);
                        $cursor--;
                        if ($echo !== false) {
                            echo "\x08 \x08";
                        }
                    }
                    break;

                case $key::TAB:
                    $count = count($input);
                    echo $this->_getAutoComplete($input);
                    echo $prompt . implode('', $input);
                    $cursor += count($input) - $count;
                    break;


                default:
                    $cursor++;
                    array_push($input, $char);
                    if ($echo !== false) {
                        echo is_string($echo) ? $echo : $char;
                    }
                    break;
            }
        }

        return implode('', $input);
    }

    /**
     * Calls the autocomplete callback and filters the result for
     * printing.
     * 
     * @param string $input
     * @access protected
     * @return string
     */
    protected function _getAutoComplete(&$input)
    {
        if (is_callable($this->_autoComplete)) {
            $line = implode('', $input);
            $options = call_user_func($this->_autoComplete, $line);
            asort($options);
            $matching = array();
            $commands = explode(' ', $line);
            $last = array_pop($commands);
            if ($last) {
                foreach ($options as $option) {
                    if (strpos($option, $last) === 0) {
                        $matching[] = $option;
                    }
                }
            } else {
                $matching = $options;
            }
            $count = count($matching);
            if ($count == 0) {
                return "\r";
            } elseif ($count == 1) {
                $commands[] = $matching[0] . ' ';
                $input = str_split(implode(' ', $commands));
                return "\r";
            }

            return "\n" . implode(' ', $matching) . "\n";
        }
        return "\r";
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

    /**
     * Clears any buffered bytes on STDIN.
     * 
     * @access protected
     * @return void
     */
    protected function _clear()
    {
        $read    = array(STDIN);
        $write   = NULL;
        $exclude = NULL;
        stream_select($read, $write, $exclude, 0);
        stream_set_blocking(STDIN, 0);
        stream_get_contents(STDIN, -1);
    }
}
