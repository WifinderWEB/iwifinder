<?php

namespace PiZone\LayoutBundle\Controller;

use PiZone\BackendBundle\Controller\AEditController;
use PiZone\BackendBundle\Controller\IEditController;

/**
 * WebItem controller.
 *
 */
class EditController extends AEditController implements IEditController
{
    public function __construct(){
        $this->model = 'PiZone\LayoutBundle\Entity\Layout';
        $this->form = 'PiZone\LayoutBundle\Form\LayoutType';
        $this->routeList['update'] = 'layout_update';
        $this->routeList['delete'] = 'layout_delete';
    }
}