<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
  function findUsersThatHaveToPay ()
  {
    $qb = $this->createQueryBuilder('e');
    $qb->select('e')
      ->leftJoin('e.orders', 'o')
      ->where('o.isWaiting = :isWaiting')
      ->andWhere('o.paid = :paid')
      ->setParameter('isWaiting', false)
      ->setParameter('paid', false);

    $q = $qb->getQuery();
    return $q->getResult();
  }
}
