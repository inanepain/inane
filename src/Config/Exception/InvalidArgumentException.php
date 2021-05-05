<?php
/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Exception
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\Config\Exception;

use Inane\Exception\ExceptionInterface;
use Inane\Exception\InvalidArgumentException as BaseInvalidArgumentException;

/**
 * Exception thrown if an argument is not of the expected type.
 *
 * @package Inane\Exception
 * @implements \Inane\Exception\ExceptionInterface
 * @version 0.2.0
 */
class InvalidArgumentException extends BaseInvalidArgumentException implements ExceptionInterface {}