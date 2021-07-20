<?php
/**
 * Request Interface
 * 
 * PHP version 8
 * 
 * @author Philip Michael Raab <peep@inane.co.za>
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
