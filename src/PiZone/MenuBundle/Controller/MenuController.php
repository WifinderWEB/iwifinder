<?php

namespace PiZone\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Session\Session;

class MenuController extends Controller {

    public function mainMenuAction($alias = 'main') {
        $items = array();
        $items[] = array('id' => 2, 'title' => 'Контент', 'link' => 'PiZone_LandingBundle_Content_list');
        $items[] = array('id' => 3, 'title' => 'WebItem', 'link' => 'PiZone_WebItemBundle_Web_item_list');
//        $items[] = array('id' => 4, 'title' => 'Галереи', 'child' => array(
//                array('id' => 5, 'title' => 'Фото галереи', 'link' => 'Esser_ImageGalleryBundle_Image_category_list'),
//                array('id' => 6, 'title' => 'Видео галереи', 'link' => 'Esser_VideoGalleryBundle_Video_category_list'),
//        ));
        $items[] = array('id' => 13, 'title' => 'Меню', 'link' => 'PiZone_MenuBundle_Menu_list');
//        if (true === $this->container->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
//            $items[] = array('id' => 8, 'title' => 'Пользователи', 'link' => 'PiZone_UserBundle_User_list');
//        }

        return $this->render(
                        'PiZoneMenuBundle:Menu:_main.html.twig', array('items' => $items)
        );
    }

    public function topMenuAction($menu, $alias = null) {
        $em = $this->getDoctrine()->getRepository('PiZoneMenuBundle:MenuItem');
        $items = $em->GetItemsTopLevelForParentAlias($menu);

        $obj = $em->GetPathForAlias($alias);
        $path = array();
        if ($obj) {
            foreach ($obj as $one) {
                $path[] = $one->getId();
            }
        }
        return $this->render(
                        'PiZoneMenuBundle:Menu:_top_menu.html.twig', array(
                    'items' => $items,
                    'path' => $path,
                    'alias' =>$alias
                        )
        );
    }

    public function siteMapAction(){
        $em = $this->getDoctrine()->getRepository('PiZoneMenuBundle:MenuItem');
        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>',
            'nodeDecorator' => function($node) {
                return '<a href="'.$this->get('router')->generate('PiZone_frontend_content_show', array('alias' => $node['alias'])).'">'.$node['title'].'</a>';
            }
        );

        $tree = $em->childrenHierarchy(
            $em->findOneBy(array('alias' => 'top_menu')), /* starting from root nodes */
            false, /* true: load all children, false: only direct */
            $options
        );


        return $this->render(
            'PiZoneMenuBundle:Menu:siteMap.html.twig', array(
                'items' => $tree
            )
        );
    }

    public function siteMapXmlAction(){
        $em = $this->getDoctrine()->getRepository('PiZoneMenuBundle:MenuItem');
        $tree = $em->children($em->findOneBy(array('alias' => 'top_menu')));

        $urls = array();
        $hostname = $this->get('request')->getSchemeAndHttpHost();

        foreach ($tree as $item) {
            $urls[] = array(
                'loc' => $this->get('router')->generate('PiZone_frontend_content_show', array('alias' => $item->getAlias())),
                'priority' => '1'
            );
        }

        return $this->render('PiZoneMenuBundle:Menu:siteMap.xml.twig', array(
            'urls'     => $urls,
            'hostname' => $hostname
        ));
    }
}
