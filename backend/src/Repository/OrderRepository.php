<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserInterface;

class OrderRepository extends EntityRepository
{
    function findWaitingOrdersForUser (UserInterface $user)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e')
            ->leftJoin('e.user', 'u')
            ->where('u.username = :username')
            ->andWhere('e.isWaiting = :isWaiting')
            ->setParameter('username', $user->getUsername())
            ->setParameter('isWaiting', true);

        $q = $qb->getQuery();
        return $q->getResult();
    }

  function findNotWaitingOrdersForUser (UserInterface $user)
  {
    $qb = $this->createQueryBuilder('e');
    $qb->select('e')
      ->leftJoin('e.user', 'u')
      ->where('u.username = :username')
      ->andWhere('e.isWaiting = :isWaiting')
      ->orderBy('e.id', 'DESC')
      ->setParameter('username', $user->getUsername())
      ->setParameter('isWaiting', false);

    $q = $qb->getQuery();
    return $q->getResult();
  }

    function findWaitingOrders ()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e')
            ->where('e.isWaiting = :isWaiting')
            ->setParameter('isWaiting', true);

        $q = $qb->getQuery();
        return $q->getResult();
    }

    function findNotWaitingOrders ()
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('e')
            ->where('e.isWaiting = :isWaiting')
            ->orderBy('e.id', 'DESC')
            ->setParameter('isWaiting', false);

        $q = $qb->getQuery();
        return $q->getResult();
    }

    function findOrdersToPayForUser(UserInterface $user)
    {
      $qb = $this->createQueryBuilder('e');
      $qb->select('e')
        ->leftJoin('e.user', 'u')
        ->where('u.username = :username')
        ->andWhere('e.isWaiting = :isWaiting')
        ->andWhere('e.paid = :paid')
        ->setParameter('username', $user->getUsername())
        ->setParameter('isWaiting', false)
        ->setParameter('paid', false);

      $q = $qb->getQuery();
      return $q->getResult();
    }

    function findLastWaitingOrderForUser(UserInterface $user)
    {
      $qb = $this->createQueryBuilder('e');
      $qb->select('e')
        ->leftJoin('e.user', 'u')
        ->where('u.username = :username')
        ->andWhere('e.isWaiting = :isWaiting')
        ->orderBy('e.id', 'DESC')
        ->setMaxResults(1)
        ->setParameter('username', $user->getUsername())
        ->setParameter('isWaiting', true);

      $q = $qb->getQuery();
      return $q->getOneOrNullResult();
    }
}
