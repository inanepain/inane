<?php
namespace Inane\Http\Request;

/**
 * IRequestAware
 * 
 * @version 0.5.0
 */
interface IRequestAware {
    /**
     * set: request
     * 
     * @param IRequest $request request
     * @return mixed 
     */
    public function setRequest(IRequest $request);
}
