<?php
namespace PiZone\WebItemBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class WebItemRepository extends EntityRepository{
    public function GetItemByAlias($alias){
        return $this->findOneBy(array('alias' => $alias, 'is_active' => true));
    }

    public function retriveWebItem($request){
        $query = $this->_em->createQueryBuilder()
            ->select("i")
            ->from("PiZoneWebItemBundle:WebItem", "i")
            ->orderBy('i.id','desc')
            ->getQuery()->execute();

        return $query;
    }
}
