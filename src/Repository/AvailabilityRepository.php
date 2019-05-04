<?php

namespace App\Repository;

use App\Entity\Availability;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Availability|null find($id, $lockMode = null, $lockVersion = null)
 * @method Availability|null findOneBy(array $criteria, array $orderBy = null)
 * @method Availability[]    findAll()
 * @method Availability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Availability::class);
    }

    /**
     * Return current availability for an event and player
     *
     * @param User $user
     * @param Event $event
     * @return mixed
     */
    public function findByUserAndEvent(User $user, Event $event)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.player', 'p')
            ->leftJoin('a.event', 'e')
            ->where('p.id = :user')
            ->andWhere('e.id = :event')
            ->setParameter('user', $user->getId())
            ->setParameter('event', $event->getId())
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Availability
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
