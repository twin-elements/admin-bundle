<?php

namespace TwinElements\AdminBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use TwinElements\AdminBundle\Entity\AdminUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method AdminUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminUser[]    findAll()
 * @method AdminUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 */
class AdminUserRepository extends ServiceEntityRepository
{
    public function __construct( ManagerRegistry $registry ) {
        parent::__construct( $registry, AdminUser::class );
    }
}
