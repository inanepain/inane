<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * PHP version 8
 *
 * @author Philip Michael Raab <peep@inane.co.za>
 * @package Inane\Debug
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2021 Philip Michael Raab <philip@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Debug;

use Attribute;

/**
 * Silence
 * 
 * Attribute to silence Dumper for a class or/then method<br/>
 * 
 * Silence priority, higher level only filter down if not silent:
 * - Dumper enabled
 * - Class Silence => false / No Silence Attribute
 * - Method Silence
 * 
 * @version 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Silence {
    public function __construct(
        private bool $on = true,
    ) {
    }

    public function __invoke() {
        return $this->on;
    }
}
