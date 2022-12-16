<?php

namespace Framework\Service;

use Symfony\Component\HttpFoundation\Session\Session;

interface UserSessionManager {
    
    public function updateSession(); 

    public function get($value); 

    public function set($key, $value);

    public function has($key);

    public function remove($key);
}
