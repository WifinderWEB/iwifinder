<?php

namespace PiZone\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PiZoneUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
