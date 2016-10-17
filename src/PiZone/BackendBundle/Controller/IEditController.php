<?php

namespace PiZone\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

interface IEditController{
    public function editAction($id);
    public function updateAction(Request $request, $id);
}