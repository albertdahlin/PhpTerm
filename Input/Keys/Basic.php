<?php

namespace Dahl\PhpTerm\Input\Keys;

/**
 * Basic key defenitions
 *
 * @copyright Copyright (C) 2015 Albert Dahlin
 * @author Albert Dahlin <info@albertdahlin.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
class Basic
{
    const   ESC         = "\x1b",
            HOME        = "\x1b[1~",
            END         = "\x1b[4~",
            PGUP        = "\x1b[5~",
            PGDOWN      = "\x1b[6~",
            DEL         = "\x1b[3~",
            INS         = "\x1b[2~",
            UP          = "\x1b[A",
            DOWN        = "\x1b[B",
            RIGHT       = "\x1b[C",
            LEFT        = "\x1b[D",
            BACKSPACE   = "\x08",
            TAB         = "\x09",
            ENTER       = "\x0a",
            SPACE       = "\x20",
            F1          = "\x1b[11~",
            F2          = "\x1b[12~",
            F3          = "\x1b[13~",
            F4          = "\x1b[14~",
            F5          = "\x1b[15~",
            F6          = "\x1b[17~",
            F7          = "\x1b[18~",
            F8          = "\x1b[10~",
            F9          = "\x1b[20~",
            F10         = "\x1b[21~",
            F11         = "\x1b[22~",
            F12         = "\x1b[24~",
            NUL         = "\0",
            CTRL_A      = "\x01",
            CTRL_B      = "\x02",
            CTRL_C      = "\x03",
            CTRL_D      = "\x04",
            CTRL_E      = "\x05",
            CTRL_F      = "\x06",
            CTRL_G      = "\x07",
            CTRL_H      = "\x08",
            CTRL_I      = "\x09",
            CTRL_J      = "\x0a",
            CTRL_K      = "\x0b",
            CTRL_L      = "\x0c",
            CTRL_M      = "\x0d",
            CTRL_N      = "\x0e",
            CTRL_O      = "\x0f",
            CTRL_P      = "\x10",
            CTRL_Q      = "\x11",
            CTRL_R      = "\x12",
            CTRL_S      = "\x13",
            CTRL_T      = "\x14",
            CTRL_U      = "\x15",
            CTRL_V      = "\x16",
            CTRL_W      = "\x17",
            CTRL_X      = "\x18",
            CTRL_Y      = "\x19",
            CTRL_Z      = "\x1a",
            CTRL_UP     = "\x1b[1;5A",
            CTRL_DOWN   = "\x1b[1;5B",
            CTRL_RIGHT  = "\x1b[1;5C",
            CTRL_LEFT   = "\x1b[1;5D",
            SHIFT_UP     = "\x1b[1;2A",
            SHIFT_DOWN   = "\x1b[1;2B",
            SHIFT_RIGHT  = "\x1b[1;2C",
            SHIFT_LEFT   = "\x1b[1;2D",
            ALT_UP     = "\x1b[1;3A",
            ALT_DOWN   = "\x1b[1;3B",
            ALT_RIGHT  = "\x1b[1;3C",
            ALT_LEFT   = "\x1b[1;3D";
}

