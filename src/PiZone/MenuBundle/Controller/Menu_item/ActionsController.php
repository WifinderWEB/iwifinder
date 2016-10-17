<?php

namespace PiZone\MenuBundle\Controller\Menu_item;

use Admingenerated\PiZoneMenuBundle\BaseMenu_itemController\ActionsController as BaseActionsController;

/**
 * ActionsController
 */
class ActionsController extends BaseActionsController
{
    protected function executeObjectDelete(\PiZone\MenuBundle\Entity\MenuItem $MenuItem)
    {
        $em = $this->getDoctrine()->getManagerForClass('PiZone\MenuBundle\Entity\MenuItem');
        $repo = $em->getRepository('PiZone\MenuBundle\Entity\MenuItem');
        $repo->remove($MenuItem);
        if($repo->verify() === true)
            $repo->recover();
        $em->flush();
        $em->clear();
    }
}
