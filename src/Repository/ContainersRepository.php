<?php

namespace App\Repository;

use App\Entity\Containers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Containers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Containers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Containers[]    findAll()
 * @method Containers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContainersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Containers::class);
    }

    public function deleteAllContainers()
    {
        return $this->createQueryBuilder('c')
            ->delete()
            ->getQuery()
            ->execute();
    }

//    public function containerToText()
//    {
//        $query =  "SELECT * from public.containers ORDER BY id ASC";
//        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
//        $stmt->execute();
//
//        $result =  $stmt->fetchAllAssociative();
//
//        $json = json_encode($result);
//        $file = fopen('requestResult.txt','w');
//        fwrite($file, $json);
//        fclose($file);
//
//        return "ok";
//    }
}
