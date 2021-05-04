<?php
namespace Inane\Http\Request;

interface IRequestAware {
    /**
     * set: request
     * 
     * @param IRequest $request request
     * @return mixed 
     */
    public function setRequest(IRequest $request);
}
