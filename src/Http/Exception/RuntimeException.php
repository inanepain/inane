<?php
/**
 * RuntimeException
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

use RuntimeException as BaseRuntimeException;

/**
 * RuntimeException
 *
 * @package Http
 * @version 0.2.0
 */
class RuntimeException extends BaseRuntimeException {
    protected $code = 700;
}
