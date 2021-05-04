<?php
namespace Inane\Http\Request;

/**
 * iRequest
 * 
 * @package Inane\Http\Request
 */
interface IRequest {
    public function getBody();
    // public function getAccept();
    public function getResponse();
}
