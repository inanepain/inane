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
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <peep@inane.co.za>
 */

namespace Inane\Http\Exception;

use Exception;

/**
 * PropertyException
 *
 * Adds Getters / Setters via magic get / set methods
 *
 * @package Exception
 * @version 0.1.0
 */
class PropertyException extends Exception {
    protected $message = 'Property Invalid: `magic_property_name`';   // exception message
    protected $code = 200;                                                  // user defined exception code
    protected $file;                                                        // source filename of exception
    protected $line;                                                        // source line of exception

    /**
     * __construct
     *
     * @param null|string $message
     * @param int $code
     * @param Exception|null $previous
     * @return void
     */
    public function __construct(?string $message = null, $code = 0, Exception $previous = null) {
        if ($previous === null) $this->message = str_replace('magic_property_name', $message, $this->message);
        if ($code >= 10 && $code <= 19) $this->message = str_replace('Invalid', 'Denied', $this->message);
        $this->code = $this->code + $code;
        $this->previous = $previous;

        $debugBacktrace = array_pop(debug_backtrace(0, 2));
        $this->file = $debugBacktrace['file'];
        $this->line = $debugBacktrace['line'];
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
