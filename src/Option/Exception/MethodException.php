<?php

/**
 * This file is part of InaneTools.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <peep@inane.co.za>
 * @package Inane\Option
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <peep@inane.co.za>
 */

namespace Inane\Option\Exception;

use Exception;

/**
 * MethodException
 *
 * Parsed Method is Invalid
 *
 * @package Inane\Option\Property
 * @version 0.1.0
 */
class MethodException extends Exception {
    protected $message = 'Method exception';   // exception message
    protected $code = 100;                        // user defined exception code
    protected $file;                            // source filename of exception
    protected $line;                            // source line of exception

    // Redefine the exception so message isn't optional
    public function __construct(?string $message = null, $code = 0, Exception $previous = null) {
        $message = $this->message . ($message ? ': ' . $message : '');
        $code = $this->code + $code;

        $debugBacktrace = array_pop(debug_backtrace(0, 3));
        $this->file = $debugBacktrace['file'];
        $this->line = $debugBacktrace['line'];

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    /**
     * magic method: __toString
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ":\n [{$this->code}]: {$this->message}";
    }
}
