<?php

namespace App\Repository;

use App\Entity\Games;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GamesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Games::class);
    }

    public function getActiveGames()
    {
        return $this
            ->createQueryBuilder('games')
            ->where('games.status = '.Games::STATUS_PENDING_USER)
            ->getQuery()
            ->execute();
    }
}