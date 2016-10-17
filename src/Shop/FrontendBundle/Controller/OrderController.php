<?php

namespace Shop\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class OrderController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render(
            'ShopFrontendBundle:Order:index.html.twig', array()
        );
    }
}