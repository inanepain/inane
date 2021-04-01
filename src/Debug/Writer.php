<?php
/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <peep@inane.co.za>
 * @package Inane\Debug
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */
namespace Inane\Debug;

use function var_dump;
use function file_put_contents;
use function print_r;
use function ob_start;
use function ob_get_clean;
use function is_array;
use function json_encode;

use const PHP_EOL;

/**
 * Log to html with pre & code tags
 *
 * @package Inane\Debug
 * @version 1.0.0
 */
class Writer {

    private static $logger = null;

    protected $message = '';

    protected $method = '';
    protected $file = '';
    protected $die = true;

    protected function __construct() {
        
    }

    public static function echo() {
        if (!static::$logger) static::$logger = new static();
        static::$logger->method = 'ECHO';
        if (static::$logger->message != '') static::$logger->message = "<pre>".static::$logger->message."</pre>";
        return static::$logger;
    }

    public static function buffer() {
        if (!static::$logger) static::$logger = new static();
        static::$logger->method = 'BUFFER';
        return static::$logger;
    }

    public static function file(?string $file = null) {
        if (!static::$logger) static::$logger = new static();
        static::$logger->method = 'FILE';

        if ($file === null && !defined('CONFIG_LOGGER_DEFAULT')) $file = 'log/debug.log';
        else if ($file === null && defined('CONFIG_LOGGER_DEFAULT')) $file = CONFIG_LOGGER_DEFAULT;

        static::$logger->file = $file;
        return static::$logger;
    }

    protected function parseData($mixed) {
        if (is_array($mixed)) $mixed = json_encode($mixed, JSON_UNESCAPED_LINE_TERMINATORS | JSON_UNESCAPED_SLASHES);
        return $mixed;
    }

    protected function label(string $label) {
        if ($this->method == 'ECHO') $this->message .= "<h3>${label}</h3>" . PHP_EOL;
        else if ($this->method == 'FILE') $this->message .= date('Y-m-d H:i:s') . ": ${label}: ";
        else $this->message .= "${label}: ";
    }

    protected function getMessage(bool $clear = true) {
        $message = $this->message;
        if ($clear) $this->message = '';
        return $message . PHP_EOL;
    }

    public function dump($mixed, ?string $label = null, ?bool $die = null) {
        static $_die = false;
        if ($die !== null) $_die = $die;

        // if ($this->method == 'FILE' && is_array($mixed)) $mixed = $this->parseData($mixed);

        if ($label) $this->label($label);
        if ($this->method == 'ECHO') $this->message .= '<pre>';
        ob_start();
        var_dump($mixed);
        $this->message .= ob_get_clean();
        if ($this->method == 'ECHO') $this->message .= '</pre>';

        if ($this->method != 'BUFFER') $this->out();
        if ($_die === true) die();
        return $this;
    }

    public function print($mixed, ?string $label = null, ?bool $die = null) {
        static $_die = false;
        if ($die !== null) $_die = $die;

        if ($this->method == 'FILE' && is_array($mixed)) $mixed = $this->parseData($mixed);
    
        if ($label) $this->label($label);
        if ($this->method == 'ECHO') $this->message .=  '<pre>';
        $this->message .= print_r($mixed, true);
        if ($this->method == 'ECHO') $this->message .=  '</pre>';

        if ($this->method != 'BUFFER') $this->out();
        if ($_die === true) die();
        return $this;
    }

    protected function out() {
        if ($this->method == 'ECHO') {
            echo $this->getMessage();
        } else if ($this->method == 'FILE') {
            file_put_contents($this->file, $this->getMessage(), FILE_APPEND);
        }
    }

    public function die(?bool $die = null) {
        if ($die !== null) $this->die = $die;
        if ($this->die === true) die();
        return $this;
    }
}