<?php

namespace Dahl\Input;

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
     * Class constructor.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->_termConfig = exec('stty -g');
        system('stty -icanon -echo -isig iutf8');
        include 'Keys/Default.php';
        $this->_keys = new Keys\Debian;
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
     * Reads one line from the keyboard. Will accept all printable characters.
     * You can set a mask of which characters to accept.
     * 
     * @param string    $prompt The prompt to display.
     * @param char|bool $echo   True | False if you want characters to be printed as typed
                                on the keyboard. You can also pass a character that should be
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
        if (!$mask) {
            $mask = '\p{L}[:print:]';
        }

        $c = null;
        echo $prompt;
        while (true) {
            $c = $this->readChar($mask, array($key::ENTER, $key::BACKSPACE));
            if ($c === $key::ENTER) {
                break;
            }

            if ($c === $key::BACKSPACE) {
                array_pop($input);
                if ($echo !== false) {
                    echo "\x08 \x08";
                }
                continue;
            }
            array_push($input, $c);
            if ($echo !== false) {
                echo is_string($echo) ? $echo : $c;
            }
        }

        return implode('', $input);
    }

    /**
     * Reads one char from STDIN. Will block until bytes are awailable.
     * 
     * @access protected
     * @return string
     */
    protected function _getc()
    {
        $r = array(STDIN);
        $w = NULL;
        $e = NULL;
        $n = stream_select($r, $w, $e, null);
        stream_set_blocking(STDIN, 0);
        $c = stream_get_contents(STDIN, -1);

        return $c;
    }

    /**
     * Clears any buffered bytes on STDIN.
     * 
     * @access protected
     * @return void
     */
    protected function _clear()
    {
        $r = array(STDIN);
        $w = NULL;
        $e = NULL;
        $n = stream_select($r, $w, $e, 0);
        stream_set_blocking(STDIN, 0);
        stream_get_contents(STDIN, -1);
    }
}
