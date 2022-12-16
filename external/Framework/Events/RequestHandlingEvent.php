<?php

namespace Framework\Events;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class RequestHandlingEvent extends Event {

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest() 
    {
        return $this->request;
    }
}