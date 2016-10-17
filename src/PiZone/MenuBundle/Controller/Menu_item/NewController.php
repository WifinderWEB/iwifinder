<?php

namespace PiZone\MenuBundle\Controller\Menu_item;

use Admingenerated\PiZoneMenuBundle\BaseMenu_itemController\NewController as BaseNewController;

/**
 * NewController
 */
class NewController extends BaseNewController
{
    public function indexAction() {
        $menuId = $this->get('session')->get('MenuId');
        if (!$menuId)
            return new RedirectResponse($this->generateUrl("PiZone_MenuBundle_Menu_list"));

        $MenuItem = $this->getNewObject();

        $form = $this->createForm($this->getNewType(), $MenuItem, $this->getFormOptions($MenuItem));

        return $this->render('PiZoneMenuBundle:Menu_itemNew:index.html.twig', $this->getAdditionalRenderParameters($MenuItem) + array(
            "MenuItem" => $MenuItem,
            "form" => $form->createView(),
        ));
    }

    protected function saveObject(\PiZone\MenuBundle\Entity\MenuItem $MenuItem) {
        $em = $this->getDoctrine()->getManagerForClass('PiZone\MenuBundle\Entity\MenuItem');
        
        $menu = $this->getDoctrine()->getManager()->getRepository('PiZoneMenuBundle:Menu')->find($this->get('session')->get('MenuId'));
                
        $MenuItem->setMenu($menu);
        $em->persist($MenuItem);
        $em->flush();
    }
}
