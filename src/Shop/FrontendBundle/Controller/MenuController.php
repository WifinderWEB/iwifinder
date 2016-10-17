<?php

namespace Shop\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MenuController extends Controller {

    public function categoriesAction() {
        $categories = $this->get('catalog_api')->getCategoryTree();
        return $this->render(
            'ShopFrontendBundle:Menu:_categories.html.twig', array(
                'items' => $categories['result']['items']
            )
        );
    }
//    public function topMenuAction($alias) {
//        $em = $this->getDoctrine()->getRepository('ShopMenuBundle:MenuItem');
//        $items = $em->GetItemsTopLevelForParentAlias($alias);
//
//        return $this->render(
//             'ShopFrontendBundle:Menu:_top_menu.html.twig', array('items' => $items)
//        );
//    }

    public function breadCrumbAction($alias) {
        $crumbs = $this->get('catalog_api')->getPathCategory($alias);

        return $this->render(
            'ShopFrontendBundle:Menu:_breadCrumb.html.twig', array(
                'items' => $crumbs,
                'alias' => $alias)
        );
    }

    public function sitemapAction(){
        $content = array();

        $em = $this->getDoctrine()->getRepository('ShopMenuBundle:MenuItem');
        $items = $em->GetItemsTopLevelForParentAlias('top');

        $catalog = $this->get('catalog_api')->getList();

        foreach($items as $one){
            $content[] = array(
                'id' => $one->getId(),
                'title' => $one->getTitle(),
                'link' => $this->get('router')->generate('shop_frontend_content_show', array('alias' => $one->getAlias())),
                'level' => $one->getLevel()
            );
        }
        $content[] = array(
            'id' => '',
            'title' => 'Продукция',
            'link' => $this->get('router')->generate('shop_frontend_catalog_list'),
            'level' => 1
        );
        foreach($catalog['result']['items'] as $item){
            $content[] = array(
                'id' => $item['id'],
                'title' => $item['title'],
                'link' => $this->get('router')->generate('shop_frontend_catalog_show', array('alias' => $item['alias'])),
                'level' => $item['level']+ 2
            );
        }


        return $this->render(
            'ShopFrontendBundle:Menu:sitemap.html.twig',
            array(
                'content' => $content
            )
        );
    }
}

