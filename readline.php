<?php
$termConfig = exec('stty -g');
system('stty -icanon -echo -isig iutf8');
dahbug::setData('output', 'print');
dahbug::setData('ascii_notation', 'caret');

$r = array(STDIN);
$w = NULL;
$e = NULL;

$abort = false;

while (!$abort) {
    $n = stream_select($r, $w, $e, null);
    stream_set_blocking(STDIN, 0);

    $c = stream_get_contents(STDIN, -1);
    $len = strlen($c);
    if ($len > 1) {
        if ($len > 2) {
            $last = substr($c, -1);
            $csi = substr($c, 0, 2);
            $code = substr($c, 2, -1);
        } else {
            $csi = substr($c, 0, 1);
            $last = substr($c, -1, 1);
            $code = '';
        }

        switch ($last) {
            case 'A':
                if ($csi == "\033[") {
                    echo "\nArrow up\n";
                }
                if ($csi == "\033O") {
                    echo "\nCtrl + Arrow up\n";
                }
                break;
            case 'R':
                $screen = explode(';', $code);
                echo "Screen is {$screen[0]} x {$screen[1]}\n";
                break;
        }
        switch ($c) {
            case "\033[B":
                echo "\nArrow down\n";
                break;
            case "\033[C":
                echo "\nArrow right\n";
                break;
            case "\033[D":
                echo "\nArrow left\n";
                break;
            case "\033[1~":
                echo "\nHome\n";
                break;
            case "\033[2~":
                echo "\nInsert\n";
                break;
            case "\033[3~":
                echo "\nDelete\n";
                break;
            case "\033[4~":
                echo "\nEnd\n";
                break;
            case "\033[5~":
                echo "\nPg Up\n";
                break;
            case "\033[6~":
                echo "\nPg Dn\n";
                break;
            case "\033[11~":
                echo "\033[1000B";
                echo "\033[1000C";
                echo "\033[6n";
                break;
            default:
        }
    } else {
        $ord = ord($c);
        if ($ord < 32) {
            switch ($ord) {
                case 8:
                    echo "\nBackspace\n";
                    break;
                case 9:
                    echo "\nTab\n";
                    break;
                case 10:
                    echo "\n";
                    break;
                case 27:
                    echo "\nEscape pressed, exiting.\n";
                    $abort = true;
                    break;
                default:
                    echo $ord . "\n";
            }
        } elseif($ord == 127) {
            echo "\nBackspace\n";
        } else {
            echo $c;
        }
    }
}

system("stty {$termConfig}");
