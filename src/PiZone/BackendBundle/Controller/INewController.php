<?php

namespace PiZone\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

interface INewController{
    public function newAction();
    public function createAction(Request $request);
}