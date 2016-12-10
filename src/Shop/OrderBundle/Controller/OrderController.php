<?php

namespace Shop\OrderBundle\Controller;

use Shop\ApiBundle\Entity\Goods;
use Shop\OrderBundle\Entity\Order;
use Shop\OrderBundle\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class OrderController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render(
            'ShopOrderBundle:Order:index.html.twig', array()
        );
    }

    public function newAction(Request $request)
    {
        if (!$this->getUser())
            $this->redirectToRoute('shop_security_login');

        if (!$this->get('session')->has('Cart'))
            $this->redirectToRoute('shop_frontend_order_empty');

        $cart = $this->get('session')->get('Cart');
        if ($cart->getCount() == 0)
            $this->redirectToRoute('shop_frontend_order_empty');

        $entity = new Order();
        $itog = 0;
        $discount = 0;
        foreach($cart->getGoods() as $one){
            $goods = new Goods();
            $goods->setGoodsId($one['id']);
            $goods->setAlias($one['alias']);
            $goods->setTitle($one['title']);
            $goods->setArticle($one['article']);
            $goods->setCount($one['count']);
            $goods->setImagePath($one['image_path']);
            $goods->setAltImage($one['alt_image']);
            $goods->setTitleImage($one['title_image']);
            $goods->setPrice($one['price']);
            $goods->setDiscount($one['discount']);
            $goods->setOrder($entity);

            $entity->addGood($goods);
            if($one['discount'])
                $discount = $discount + $one['discount'];
            if($one['price'])
                $itog = $itog + $one['price'];
        }
        $entity->setItog($itog);
        $entity->setDiscount($discount);

        $form = $this->createCreateForm($entity);

        return $this->render(
            'ShopOrderBundle:Order:new.html.twig', array(
                'form' => $form->createView(),
                'entity' => $entity
            )
        );
    }

    public function createAction(Request $request){
        if (!$this->getUser())
            $this->redirectToRoute('shop_security_login');

        if (!$this->get('session')->has('Cart'))
            $this->redirectToRoute('shop_frontend_order_empty');

        $cart = $this->get('session')->get('Cart');
        if ($cart->getCount() == 0)
            $this->redirectToRoute('shop_frontend_order_empty');

        $entity = new Order();
        $itog = 0;
        $discount = 0;
        foreach($cart->getGoods() as $one){
            $entity->addGood($one);
            if($one->getDiscount())
                $discount = $discount + $one->getDiscount();
            if($one->getPrice())
                $itog = $itog + $one->getPrice();
        }
        $entity->setItog($itog);
        $entity->setDiscount($discount);

        $form = $this->createCreateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $this->normalizeData($request, $form);
            $order = $this->get('catalog_api')->createOrder($data);
            if ($order['result'] == 'ok') {
                if ($this->get('session')->has('Cart')) {
                    $this->get('session')->remove('Cart');
                }
                return $this->render(
                    'ShopOrderBundle:Order:result.html.twig', array(
                        'order' => $order
                    )
                );
            } else {
                $result = array(
                    'result' => 'error',
                    'message' => $order['message']
                );
            }
        }

        return $this->render(
            'ShopOrderBundle:Order:create.html.twig', array(
                'form' => $form->createView(),
                'result' => $result
            )
        );
    }

    private function createCreateForm($entity)
    {
        $form = $this->createForm(new OrderType(), $entity);

        return $form;
    }

    private function normalizeData($request, $form){
        $data = $request->request->get($form->getName());
        $data['project'] = $this->container->getParameter('project_id');
        unset($data['_token']);

        return array('catalog_orderbundle_order' => $data);
    }

    public function emptyAction(Request $request)
    {
        return $this->render(
            'ShopOrderBundle:Order:index.html.twig', array()
        );
    }
}