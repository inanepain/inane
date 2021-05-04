<?php
namespace Inane\Http\Request;

interface IRequest {
    public function getBody();
    // public function getAccept();
    public function getResponse();
}
