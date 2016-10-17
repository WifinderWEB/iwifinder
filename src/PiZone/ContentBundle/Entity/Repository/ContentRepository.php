<?php

namespace PiZone\ContentBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ContentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentRepository extends EntityRepository
{
    public function getContentByTemplateId($id){
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
            ->from("PiZoneContentBundle:Content", "c")
            ->join('c.layout', 'l')
            ->where('l.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $query;
    }

    public function getListContents(){
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
            ->from("PiZoneContentBundle:Content", "c")
            ->addOrderBy('c.title');

        return $query;
    }

    public function GetContentByAlias($alias){
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c")
            ->from("PiZoneContentBundle:Content", "c")
            ->where('c.alias = :alias')
            ->andWhere('c.is_active = true')
            ->setParameter('alias', $alias)
            ->getQuery()
            ->getOneOrNullResult();

        return $query;
    }
}
