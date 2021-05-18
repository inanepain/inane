<?php
namespace Inane\Http\Request;

/**
 * iRequest
 * 
 * @package Http
 * @version 0.5.0
 */
interface IRequest {
    public function getBody();
    // public function getAccept();
    public function getResponse();
}
