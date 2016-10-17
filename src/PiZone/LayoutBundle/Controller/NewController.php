<?php

namespace PiZone\LayoutBundle\Controller;

use PiZone\BackendBundle\Controller\ANewController;
use PiZone\BackendBundle\Controller\INewController;

/**
 * WebItem controller.
 *
 */
class NewController extends ANewController implements INewController
{
    public function __construct(){
        $this->model = 'PiZone\LayoutBundle\Entity\Layout';
        $this->form = 'PiZone\LayoutBundle\Form\LayoutType';
        $this->route['create'] = 'layout_create';
    }
}
