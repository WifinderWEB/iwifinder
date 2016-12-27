<?php

namespace Shop\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class BrandController extends Controller
{
    public function indexAction()
    {
        $catalog = $this->get('catalog_api')->getProjectList();

        if ($catalog['result'] == 'error')
            throw new NotFoundHttpException($catalog['message']);

        return $this->render(
            'ShopFrontendBundle:Brand:index.html.twig', array(
                'items' => $catalog['result']['items']
            )
        );
    }

    public function brandAction($alias)
    {
        $catalog = $this->get('catalog_api')->getTreeByAlias($alias);

        if ($catalog['result'] == 'error')
            throw new NotFoundHttpException($catalog['message']);

        return $this->render(
            'ShopFrontendBundle:Brand:brand.html.twig', array(
                'items' => $catalog['result']['items'],
                'project' => $catalog['result']['project']
            )
        );
    }

    public function showAction($brand, $alias)
    {
        $catalog = $this->get('catalog_api')->getTreeByAlias($alias, $brand);

        if ($catalog['result'] == 'error')
            throw new NotFoundHttpException($catalog['message']);

        $goods = array();
        $items = array();
        foreach($catalog['result']['items'] as $one){
            if(!isset($one['child']))
                $goods[] = $one;
            else
                $items[] = $one;
        }

        return $this->render(
            'ShopFrontendBundle:Brand:show.html.twig', array(
                'alias' => $alias,
                'items' => $items,
                'project' => $catalog['result']['project'],
                'goods' => addslashes(json_encode($goods)),
                'content' => $catalog['result']['content'],
                'count' => count($goods)
            )
        );
    }
}