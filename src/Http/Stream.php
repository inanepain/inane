<?php

/**
 * Stream
 * 
 * PHP version 8
 * 
 * @author Philip Michael Raab <peep@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Http;

use Inane\Http\Psr\Stream as PsrStream;
use Psr\Http\Message\StreamInterface;

/**
 * Stream
 * 
 * @version 0.5.0
 * 
 * @package Http
 */
class Stream extends PsrStream implements StreamInterface {
}
