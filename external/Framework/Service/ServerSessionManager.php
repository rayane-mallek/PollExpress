<?php

namespace Framework\Service;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;

class ServerSessionManager implements UserSessionManager {

    protected Session $session;

    public function updateSession() 
    {
        $this->session = new Session();
    }

    public function get($value) 
    {
        return $this->session->get($value);
    }

    public function set($key, $value)
    {
        $this->session->set($key, $value);
    }

    public function has($key) 
    {
        return $this->session->has($key);
    }

    public function remove($key)
    {
        $this->session->remove($key);
    }

    private function getFlashBag()
    {
        if ($this->has('flashes')) {
            $flashes = $this->get('flashes');
        } else {
            $flashes = new AttributeBag();
            $this->set('flashes', $flashes);
        }

        return $flashes;
    }

    public function addFlash($category, $message) 
    {
        $flashBag = $this->getFlashBag();
        $flashes = $flashBag->get($category);

        if (!$flashes) {
            $flashes = [];
        }
    
        $flashes[] = $message;

        $flashBag->set($category, $flashes);
    }

    public function consumeFlashes($category)
    {
        $flashBag = $this->getFlashBag();
        $flashes = $flashBag->get($category);
        $flashBag->remove($category);
        
        return $flashes;
    }
}
