<?php

namespace Shop\FrontendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Shop\FrontendBundle\Controller\ContentController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CatalogController extends Controller {

    public function indexAction(Request $request) {
        $catalog = $this->get('catalog_api')->getCategoryTree(3);

        if ($catalog['result'] == 'error')
            throw new NotFoundHttpException($catalog['message']);

        return $this->render(
            'ShopFrontendBundle:Catalog:index.html.twig', array(
                'items' => $catalog['result']['items']
            )
        );
    }

    public function showAction($alias, Request $request) {
        $content = $this->get('catalog_api')->getCategoryByAlias($alias);
        if ($content['result'] == 'error')
            throw new NotFoundHttpException($content['message']);

        if ($content['result']['type'] == 'section')
            return $this->render(
                'ShopFrontendBundle:Catalog:section.html.twig', array(
                    'category' => $content['result']['category'],
                    'content' => $content['result']['items'],
                    'alias' => $alias
                )
            );
        else
            return $this->render(
                'ShopFrontendBundle:Catalog:category.html.twig', array(
                    'category' => $content['result']['category'],
                    'content' => addslashes(json_encode($content['result']['content'])),
                    'filters' => $content['result']['filters'],
                    'count' => $content['result']['count'] ? $content['result']['count'] : 0,
                    'alias' => $alias
                )
            );
    }

    public function goodsAction($category, $alias){
        $content = $this->get('catalog_api')->getContentByAlias($alias);

        if ($content['result'] == 'error')
            throw new NotFoundHttpException($content['message']);

        return $this->render(
            'ShopFrontendBundle:Catalog:goods.html.twig', array(
                'alias' => $alias,
                'content' => $content['result']['item']
            )
        );
    }

    public function listAction(Request $request) {
        $catalog = $this->get('catalog_api')->getTree();

        if ($catalog['result'] == 'error')
            throw new NotFoundHttpException($catalog['message']);

        return $this->render(
                        'ShopFrontendBundle:Catalog:list.html.twig', array(
                    'content' => $catalog['result']['items']
                        )
        );
    }

    public function breadcrumbsAction($content) {
        $catalog = $this->get('catalog_api')->getBreadcrumbs($content['alias']);

        if ($catalog['result'] == 'error')
            throw new NotFoundHttpException($catalog['message']);

        return $this->render(
                        'ShopFrontendBundle:Catalog:_breadcrumbs.html.twig', array(
                    'items' => $catalog['result']['items'],
                    'content' => $content
                        )
        );
    }

    public function leftMenuAction($alias) {
        $catalog = $this->get('catalog_api')->getMenu($alias);
        if ($catalog['result'] == 'error')
            throw new NotFoundHttpException($catalog['message']);

        return $this->render(
                        'ShopFrontendBundle:Catalog:_left_menu.html.twig', array(
                    'items' => $catalog['result']['items'],
                    'alias' => $alias
                        )
        );
    }

    public function topMenuAction($alias){
        $catalog = $this->get('catalog_api')->getTree(3);

        if ($catalog['result'] == 'error')
            throw new NotFoundHttpException($catalog['message']);
        return $this->render(
            'ShopFrontendBundle:Catalog:_top_menu.html.twig', array(
                'categories' => $catalog['result']['items'],
                'alias' => $alias
            )
        );
    }

    public function orderAction($catalog_alias, $alias){
        $em = $this->getDoctrine()->getRepository('ShopContentBundle:Content');
        $content = $em->GetContentByAlias($alias);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content with alias = ['.$alias.']');
        }
        
        $catalog = $this->get('catalog_api')->getContentByAliasShort($catalog_alias);
        
        $content = $this->adaptationContent($content, $catalog);
        
        return $this->render(
            'ShopFrontendBundle:Content:index.html.twig', array(
                'content' => $content)
        );
    }

    public function searchAction(Request $request) {
        $query = $request->get('query');
        if ($query && trim($query) != ''){

            $page = $request->get('page');
            if(!$page)
                $page = 1;

            $catalog = $this->get('catalog_api')->search($query, $page);

            $pager = ceil($catalog['result']['count']/10);


            return $this->render(
                'ShopFrontendBundle:Search:list.html.twig', array(
                    'alias' => '',
                    'query' => $query,
                    'content' => addslashes(json_encode($catalog['result']['items'])),
                    'count' => $catalog['result']['count'],
                    'pager' => array('current' => $page, 'list' => $pager)
                )
            );
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
    
    private function getParameters($one){
        $paramsArray = explode(',', $one);
        
        $result = array();
        foreach($paramsArray as $param){
            $t = explode(':', $param);
            $result[trim($t[0])] = trim($t[1]); 
        }
        
        return $result;
    }
    
    private function adaptationContent($content, $catalog){
        $content->setTitle(str_replace('{{title}}', $catalog['result']['item']['title'], $content->getTitle()));
        
        if(isset($catalog['result']['item']['article']))
            $content->setTitle(str_replace('{{article}}', $catalog['result']['item']['article'], $content->getTitle()));
        else
            $content->setTitle(str_replace('{{article}}', '', $content->getTitle()));
        
        $content->setContent(str_replace('{{title}}', $catalog['result']['item']['title'], $content->getContent()));
        if(isset($catalog['result']['item']['article']))
            $content->setContent(str_replace('{{article}}', $catalog['result']['item']['article'], $content->getContent()));
        else
            $content->setContent(str_replace('{{article}}', '', $content->getContent()));
        
        $pattern = preg_match_all('/{{form\((.*)\)}}/isU', $content->getContent(), $matches, PREG_SET_ORDER);

        foreach($matches as $one){
            $params = $this->getParameters($one[1]);
            
            $component = new ContentController();
            $component->setContainer($this->container);
             
            $content->setContent(str_replace($one[0], $component->getFormAction($params['alias'], str_replace(array('"', '\''), '', $params['redirect_url']))->getContent(), $content->getContent()));
        }
        
        return $content;
    }
}
