<?php

namespace PiZone\MenuBundle\Controller\Menu;

use PiZone\BackendBundle\Controller\ANewController;
use PiZone\BackendBundle\Controller\INewController;

/**
 * NewController controller.
 *
 */
class NewController extends ANewController implements INewController
{
    public function __construct(){
        $this->model = 'PiZone\MenuBundle\Entity\Menu';
        $this->form = 'PiZone\MenuBundle\Form\Menu\MenuType';
        $this->route['create'] = 'menu_create';
    }
}
