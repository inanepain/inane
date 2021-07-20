<?php
/**
 * Request Aware
 * 
 * PHP version 8
 * 
 * @author Philip Michael Raab <peep@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Http\Request;

use Psr\Http\Message\RequestInterface;

/**
 * IRequestAware
 * 
 * @deprecated use Psr\Http\Message\RequestInterface
 * 
 * @version 0.5.0
 * 
 * @package Http
 */
interface IRequestAware {
    /**
     * set: request
     * 
     * @param RequestInterface $request request
     * @return mixed 
     */
    public function setRequest(RequestInterface $request);
}
