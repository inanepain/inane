<?php
namespace Inane\Http\Request;

use Psr\Http\Message\RequestInterface;

/**
 * IRequestAware
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
