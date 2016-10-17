<?php

namespace PiZone\MenuBundle\Controller\Menu;

use PiZone\BackendBundle\Controller\AEditController;
use PiZone\BackendBundle\Controller\IEditController;

/**
 * WebItem controller.
 *
 */
class EditController extends AEditController implements IEditController
{
    public function __construct(){
        $this->model = 'PiZone\MenuBundle\Entity\Menu';
        $this->form = 'PiZone\MenuBundle\Form\Menu\MenuType';
        $this->routeList['update'] = 'menu_update';
        $this->routeList['delete'] = 'menu_delete';
    }
}
