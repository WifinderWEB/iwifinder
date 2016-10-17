<?php

namespace PiZone\MenuBundle\Controller\Menu;

use PiZone\BackendBundle\Controller\AActionController;
use FOS\RestBundle\View\View;

/**
 * WebItem controller.
 *
 */
class ActionController extends AActionController
{
    public function __construct()
    {
        $this->model = 'PiZone\MenuBundle\Entity\Menu';
        $this->repository = 'PiZone\MenuBundle\Entity\Menu';
        $this->route['delete'] = 'menu_delete';
    }
}
