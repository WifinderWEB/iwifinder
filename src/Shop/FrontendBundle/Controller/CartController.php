<?php

namespace Shop\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CartController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render(
                'ShopFrontendBundle:Cart:index.html.twig', array()
        );
    }
}