<?php

declare(strict_types=1);

namespace Inane\Http;

use Inane\Type\Enum;

/**
 * Method
 * 
 * @method static self COPY()
 * @method static self DELETE()
 * @method static self GET()
 * @method static self LINK()
 * @method static self LOCK()
 * @method static self OPTIONS()
 * @method static self PATCH()
 * @method static self POST()
 * @method static self PROPFIND()
 * @method static self PURGE()
 * @method static self PUT()
 * @method static self UNLINK()
 * @method static self UNLOCK()
 * @method static self VIEW()
 * 
 * @version 0.9.0
 * 
 * @package Http
 */
class Method extends Enum {
    const COPY = 'COPY';
    const DELETE = 'DELETE';
    const GET = 'GET';
    const LINK = 'LINK';
    const LOCK = 'LOCK';
    const OPTIONS = 'OPTIONS';
    const PATCH = 'PATCH';
    const POST = 'POST';
    const PROPFIND = 'PROPFIND';
    const PURGE = 'PURGE';
    const PUT = 'PUT';
    const UNLINK = 'UNLINK';
    const UNLOCK = 'UNLOCK';
    const VIEW = 'VIEW';

    /**
     * @var string[] the descriptions
     */
    protected static $descriptions = [
        self::COPY => 'COPY',
        self::DELETE => 'DELETE',
        self::GET => 'GET',
        self::LINK => 'LINK',
        self::LOCK => 'LOCK',
        self::OPTIONS => 'OPTIONS',
        self::PATCH => 'PATCH',
        self::POST => 'POST',
        self::PROPFIND => 'PROPFIND',
        self::PURGE => 'PURGE',
        self::PUT => 'PUT',
        self::UNLINK => 'UNLINK',
        self::UNLOCK => 'UNLOCK',
        self::VIEW => 'VIEW',
    ];

    /**
     * @var string[] the defaults
     */
    protected static $defaults = [
        self::COPY => 'COPY',
        self::DELETE => 'DELETE',
        self::GET => 'GET',
        self::LINK => 'LINK',
        self::LOCK => 'LOCK',
        self::OPTIONS => 'OPTIONS',
        self::PATCH => 'PATCH',
        self::POST => 'POST',
        self::PROPFIND => 'PROPFIND',
        self::PURGE => 'PURGE',
        self::PUT => 'PUT',
        self::UNLINK => 'UNLINK',
        self::UNLOCK => 'UNLOCK',
        self::VIEW => 'VIEW',
    ];
}