<?php

namespace Shop\OrderBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends EntityRepository
{
    public function findByUser($user){
        $q = $this->_em->createQueryBuilder();
        $query = $q->select("c, g")
            ->from("ShopOrderBundle:Order", "c")
            ->leftJoin('c.goods', 'g')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $query;
    }
}