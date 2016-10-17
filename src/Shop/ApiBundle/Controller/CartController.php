<?php

namespace Shop\ApiBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Shop\ApiBundle\Entity\Cart;

class CartController extends FOSRestController
{
    public function getCartInfoAction()
    {
        $result = array(
            'id' => $this->get('session')->getId(),
            'count' => 0,
            'goods' => array()
        );

        if($this->get('session')->has('Cart')){
            $cart = $this->get('session')->get('Cart');
            $result['count'] = $cart->getCount();
            $result['goods'] = $cart->getGoods();
        }

        $view = new View($result);
        return $this->handleView($view);
    }

    public function addToCartAction($alias){
        $goods = $this->get('catalog_api')->getContentByAlias($alias);

        if($goods['result'] == 'error')
            throw new NotFoundHttpException($goods['message']);

        if($this->get('session')->has('Cart')){
            $cart = $this->get('session')->get('Cart');
            $cart->addGoods($goods['result']['item']);
        }
        else{
            $cart = new Cart();
            $cart->addGoods($goods['result']['item']);
            $this->get('session')->set('Cart', $cart);
        }

        $result = array(
            'id' => $this->get('session')->getId(),
            'count' => $cart->getCount(),
            'goods' => $cart->getGoods()
        );

        $view = new View($result);
        return $this->handleView($view);
    }

    public function clearCartAction(){
        if($this->get('session')->has('Cart')){
            $cart = $this->get('session')->get('Cart');
            $cart->clear();
        }

        $result = array(
            'id' => $this->get('session')->getId(),
            'count' => $cart->getCount(),
            'goods' => $cart->getGoods()
        );

        $view = new View($result);
        return $this->handleView($view);
    }

    public function removeFromCartAction($id){
        if($this->get('session')->has('Cart')){
            $cart = $this->get('session')->get('Cart');
            $cart->remove($id);
        }

        $result = array(
            'id' => $this->get('session')->getId(),
            'count' => $cart->getCount(),
            'goods' => $cart->getGoods()
        );

        $view = new View($result);
        return $this->handleView($view);
    }

    public function setCountGoodsAction($id, $count){
        $result = array(
            'id' => $this->get('session')->getId(),
            'count' => 0,
            'goods' => array()
        );

        if($this->get('session')->has('Cart')){
            $cart = $this->get('session')->get('Cart');
            $cart->setCountGoods($id, $count);
        }

        $result = array(
            'id' => $this->get('session')->getId(),
            'count' => $cart->getCount(),
            'goods' => $cart->getGoods()
        );

        $view = new View($result);
        return $this->handleView($view);
    }
}
