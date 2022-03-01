<?php

/**
 * Inane\Tools
 *
 * Http
 *
 * PHP version 8.1
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

namespace Inane\Http;

/**
 * HttpMethod
 *
 * @version 0.9.0
 *
 * @package Http
 */
enum HttpMethod: string {
    case Copy = 'COPY';
    case Delete = 'DELETE';
    case Get = 'GET';
    case Link = 'LINK';
    case Lock = 'LOCK';
    case Options = 'OPTIONS';
    case Patch = 'PATCH';
    case Post = 'POST';
    case PropFind = 'PROPFIND';
    case Purge = 'PURGE';
    case Put = 'PUT';
    case Unlink = 'UNLINK';
    case Unlock = 'UNLOCK';
    case View = 'VIEW';
}
