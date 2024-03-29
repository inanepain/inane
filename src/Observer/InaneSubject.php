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
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Observer;

/**
 * Observer pattern: Subject
 *
 * @package Inane\Observer\InaneSubject
 * @version 0.1.0
 */
abstract class InaneSubject {
	abstract function attach(InaneObserver $observer_in);
	abstract function detach(InaneObserver $observer_in);
	abstract function notify();
}
