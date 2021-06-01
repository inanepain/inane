<?php
/**
 * InvalidArgumentException
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package dSpace\Exception
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Http\Exception;

use InvalidArgumentException as BaseInvalidArgumentException;

/**
 * InvalidArgumentException
 *
 * @package Http
 * @version 0.2.0
 */
class InvalidArgumentException extends BaseInvalidArgumentException {
    protected $code = 750;
}
