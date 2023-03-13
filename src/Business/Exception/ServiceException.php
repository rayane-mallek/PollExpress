<?php

namespace TheFeed\Business\Exception;

use Exception;

class ServiceException extends Exception {

    public function __construct($message)
    {
        $this->message = $message;
    }
}
