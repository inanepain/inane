<?php
namespace Inane\Http\Request;

/**
 * iRequest
 * 
 * @package Inane\Http\Request
 * @version 0.5.0
 */
interface IRequest {
    public function getBody();
    // public function getAccept();
    public function getResponse();
}