<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Http
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\Option;

/**
 * BitwiseFlagTrait
 *
 * @package Inane\Option
 * @version 0.1.0
 */
trait BitwiseFlagTrait {
    /**
     * Set flags
     * 
     * @var mixed
     */
    protected $flags;

    /**
     * Is flag set
     * 
     * @param int $flag the flag to test
     * @return bool true if the flag is set
     */
    protected function isFlagSet(int $flag): bool {
        return (($this->flags & $flag) == $flag);
    }


    /**
     * Set the flag
     * 
     * @param int $flag 
     * @param bool $value true or false to set it on or off
     * @return self
     */
    protected function setFlag(int $flag, bool $value = true): self {
        if ($value) $this->flags |= $flag;
        else $this->flags &= ~$flag;

        return $this;
    }
}
