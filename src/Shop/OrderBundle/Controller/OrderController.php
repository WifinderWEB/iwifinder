<?php

namespace Shop\OrderBundle\Controller;

use Shop\OrderBundle\Entity\Goods;
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
        if (!$this->getUser()) {
            return $this->redirectToRoute('shop_security_login');
        }

        if (!$this->get('session')->has('Cart')) {
            return $this->redirectToRoute('shop_frontend_order_empty');
        }

        $cart = $this->get('session')->get('Cart');
        if (!$cart || $cart->getCount() == 0) {
            return $this->redirectToRoute('shop_frontend_order_empty');
        }

        $entity = new Order();
        $itog = 0;
        $discount = 0;
        foreach($cart->getGoods() as $one){
            $goods = new Goods();
            $goods->setGoodsId($one['id']);
            $goods->setAlias($one['alias']);
            $goods->setTitle($one['title']);
            $goods->setArticle(isset($one['article']) ? $one['article'] : '');
            $goods->setCount(isset($one['count']) ? $one['count'] : 0);
            $goods->setImagePath(isset($one['image_path']) ? $one['image_path'] : '');
            $goods->setAltImage(isset($one['alt_image']) ? $one['alt_image'] : '');
            $goods->setTitleImage(isset($one['title_image']) ? $one['title_image'] : '');
            $goods->setPrice(isset($one['price']) ? $one['price'] : 0);
            $goods->setDiscount(isset($one['discount']) ? $one['discount'] : 0);
            $goods->setOrder($entity);

            $entity->addGood($goods);
            if(isset($one['discount']))
                $discount = $discount + $one['discount'];
            if(isset($one['price']))
                $itog = $itog + $one['price'];
        }
        $entity->setItog($itog-$discount);
        $entity->setDiscount($discount);
        $entity->setUser($this->getUser());

        $form = $this->createCreateForm($entity);

        return $this->render(
            'ShopOrderBundle:Order:new.html.twig', array(
                'form' => $form->createView(),
                'entity' => $entity,
                'user' => $this->getUser()
            )
        );
    }

    public function createAction(Request $request){
        if (!$this->getUser())
            $this->redirectToRoute('shop_security_login');

        if (!$this->get('session')->has('Cart'))
            $this->redirectToRoute('shop_frontend_order_empty');

        $cart = $this->get('session')->get('Cart');

        if (!$cart || $cart->getCount() == 0)
            $this->redirectToRoute('shop_frontend_order_empty');

        $entity = new Order();
        $itog = 0;
        $discount = 0;
        foreach($cart->getGoods() as $one){
            $goods = new Goods();
            $goods->setGoodsId($one['id']);
            $goods->setAlias($one['alias']);
            $goods->setTitle($one['title']);
            $goods->setArticle(isset($one['article']) ? $one['article'] : '');
            $goods->setCount(isset($one['count']) ? $one['count'] : 0);
            $goods->setImagePath(isset($one['image_path']) ? $one['image_path'] : '');
            $goods->setAltImage(isset($one['alt_image']) ? $one['alt_image'] : '');
            $goods->setTitleImage(isset($one['title_image']) ? $one['title_image'] : '');
            $goods->setPrice(isset($one['price']) ? $one['price'] : 0);
            $goods->setDiscount(isset($one['discount']) ? $one['discount'] : 0);
            $goods->setOrder($entity);

            $entity->addGood($goods);
            if(isset($one['discount']))
                $discount = $discount + $one['discount'];
            if(isset($one['price']))
                $itog = $itog + $one['price'];
        }
        $entity->setItog($itog-$discount);
        $entity->setDiscount($discount);
        $entity->setUser($this->getUser());
        $entity->setProjectId($this->container->getParameter('project_id'));

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);


        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager('user');
                $em->persist($entity);
                $em->flush();

                if ($this->get('session')->has('Cart')) {
                    $this->get('session')->remove('Cart');
                }
                return $this->render(
                    'ShopOrderBundle:Order:result.html.twig', array(
                        'order' => $entity
                    )
                );
            }
            catch (\Exception $e){
                $result = array(
                    'result' => 'error',
                    'message' => $e->getMessage()
                );
            }
        }

        return $this->render(
            'ShopOrderBundle:Order:create.html.twig', array(
                'form' => $form->createView(),
                'entity' => $entity,
                'user' => $this->getUser()
            )
        );
    }

    private function createCreateForm($entity)
    {
        $form = $this->createForm(new OrderType(), $entity);

        return $form;
    }


    public function emptyAction(Request $request)
    {
        return $this->render(
            'ShopOrderBundle:Order:index.html.twig', array()
        );
    }
}