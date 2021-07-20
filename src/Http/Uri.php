<?php

/**
 * Uri
 * 
 * PHP version 8
 * 
 * @author Philip Michael Raab <peep@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Http;

use Inane\Http\Psr\Uri as PsrUri;
use Psr\Http\Message\UriInterface;

/**
 * Uri
 * 
 * @version 0.5.0
 * 
 * @package Http
 */
class Uri extends PsrUri implements UriInterface {
}
