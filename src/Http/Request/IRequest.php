<?php

/**
 * Inane\Tools
 *
 * Http
 *
 * PHP version 8
 *
 * @package Inane\Tools
 * @author Philip Michael Raab<peep@inane.co.za>
 *
 * @license MIT
 * @license https://raw.githubusercontent.com/CathedralCode/Builder/develop/LICENSE MIT License
 *
 * @copyright 2013-2019 Philip Michael Raab <peep@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Http\Request;

/**
 * iRequest
 *
 * @deprecated Use Psr\Http\Message\RequestInterface
 *
 * @package Http
 *
 * @version 0.5.0
 */
interface IRequest {
    public function getBody();
    public function getResponse();
}
