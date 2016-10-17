<?php

namespace PiZone\BackendBundle\Entity;

abstract class Entity{
    public function get($field){
        $method = 'get' . ucfirst($field);
        if(method_exists($this, $method)){
            return $this->$method();
        }
        return null;
    }
}