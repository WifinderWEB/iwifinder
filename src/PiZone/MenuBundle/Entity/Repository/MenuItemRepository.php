<?php

namespace PiZone\MenuBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use PiZone\MenuBundle\Entity\MenuItem;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class MenuItemRepository extends NestedTreeRepository {

    private $container;

    public function __construct(EntityManager $em, ClassMetadata $class) {
        parent::__construct($em, $class);
        global $kernel;
        if($kernel instanceOf \AppCache) 
            $kernel = $kernel->getKernel();
        $this->container = $kernel->getContainer();
    }
    
    private function getMenuId(){
        return $this->container->get('session')->get('MenuId');
    }
    
    public function getListItem(){
        return $this->_em->createQueryBuilder()
                ->select("i")
                ->from("PiZoneMenuBundle:MenuItem", "i")
                ->andWhere('i.menu_id = :menu_id')
                ->addOrderBy('i.lft')
                ->setParameter('menu_id', $this->getMenuId());
    }
    
    public function GetItemForMenu($id) {
        return $this->_em->createQueryBuilder()
                ->select("i")
                ->from("PiZoneMenuBundle:MenuItem", "i")
                ->where('i.menu_id = :menu_id')
                ->orderBy('i.lft')
                ->setParameter('menu_id', $id);
    }
    
    public function GetTreeForMenu($id) {
        return $this->_em->createQueryBuilder()
                ->select("i")
                ->from("PiZoneMenuBundle:MenuItem", "i")
                ->where('i.menu_id = :menu_id')
                ->andWhere('i.is_active = true')
                ->andWhere('i.level > 0')
                ->orderBy('i.lft')
                ->setParameter('menu_id', $id)
                ->getQuery()->getResult();
    }

    public function GetItemsByMenuId($menu_id) {
        $entities = $this->_em->createQueryBuilder()
                ->select("i")
                ->from("PiZoneMenuBundle:MenuItem", "i")
                ->where('i.menu_id = :menu_id')
                ->andWhere('i.is_visible = true')
                ->setParameter('menu_id', $menu_id)
                ->orderBy('i.lft')
                ->getQuery()
                ->getResult();
        return $entities;
    }

    public function GetItemsTopLevelForParentAlias($alias) {
        $entities = $this->_em->createQueryBuilder()
                ->select("i")
                ->from("PiZoneMenuBundle:MenuItem", "i")
                ->innerJoin("PiZoneMenuBundle:MenuItem", "j")
                ->where("j.alias = :alias")
                ->setParameter('alias', $alias)
                ->andWhere('i.parent_id = j.id')
                ->andWhere('i.is_visible = true')
                ->andWhere('i.is_active = true')
                ->andWhere('i.level = j.level + 1')
                ->orderBy('i.lft')
                ->getQuery()
                ->getResult();

        return $entities;
    }

    public function GetItemsForParentAlias($alias) {

        $entities = $this->_em->createQuery(
                "SELECT parent FROM PiZoneMenuBundle:MenuItem AS node,
                PiZoneMenuBundle:MenuItem AS parent
                WHERE node.lft 
                BETWEEN parent.lft 
                AND parent.rgt 
                AND node.alias = :alias 
                AND node.root = parent.root 
                AND parent.level = 1")
                ->setParameter('alias', $alias)
                ->getOneOrNullResult();
        
        $tree = $this->GetItemsTopLevelForParentAlias($entities->getAlias());
        return array('parent' => $entities, 'tree' => $tree);
    }

    public function GetPathForAlias($alias) {
        $path = array();
        $node = $this->_em->createQueryBuilder()
                ->select("i")
                ->from('PiZoneMenuBundle:MenuItem', 'i')
                ->where('i.alias = :alias')
                ->setParameter('alias', $alias)
                ->getQuery()
                ->getOneOrNullResult();

        if($node)
            $path = $this->getPath($node);
        return $path;
    }
    
    public function GetItemsLevelForAlias($alias) {
        $entities = $this->_em->createQueryBuilder()
                ->select("i")
                ->from("PiZoneMenuBundle:MenuItem", "i")
                ->innerJoin("PiZoneMenuBundle:MenuItem", "j")
                ->where("j.alias = :alias")
                ->setParameter('alias', $alias)
                ->andWhere('i.parent_id = j.parent_id')
                ->andWhere('i.is_visible = true')
                ->andWhere('i.is_active = true')
                ->andWhere('i.level = j.level')
                ->orderBy('i.lft')
                ->getQuery()
                ->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
                ->getResult();

        return $entities;
    }
}