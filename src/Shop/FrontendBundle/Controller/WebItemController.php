<?php

namespace Shop\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class WebItemController extends Controller
{
    public function indexAction($alias)
    {
        $item = $this->getDoctrine()->getRepository('PiZoneWebItemBundle:WebItem')->findOneBy(array('is_active' => true, 'alias' => $alias));
        return $this->render(
            'ShopFrontendBundle:WebItem:index.html.twig', array(
                'content' => $item ? $item->getContent() : ''
            )
        );
    }
}