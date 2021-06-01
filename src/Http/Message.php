<?php

/**
 * Message
 * 
 * PHP version 8
 */

declare(strict_types=1);

namespace Inane\Http;

use Inane\Http\Psr\Message as PsrMessage;
use Psr\Http\Message\MessageInterface;

/**
 * Message
 * 
 * @version 0.6.0
 * 
 * @package Http
 */
class Message extends PsrMessage implements MessageInterface {
}
