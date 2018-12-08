<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UsersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function dropNonActiveUsers()
    {
        return $this
            ->createQueryBuilder('users')
            ->andWhere('users.isActive = 0')
            ->delete()
            ->getQuery()
            ->execute();
    }
}