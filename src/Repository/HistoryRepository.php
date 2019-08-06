<?php

namespace App\Repository;

use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function getAllQuery()
    {
        return $this->createQueryBuilder('h')
            ->orderBy('h.id', 'DESC');

    }

    public function getStatistics()
    {

        $stats = $this->createQueryBuilder('h')
            ->select('avg(h.temp) as avg_temp, min(h.temp) as min_temp, max(h.temp) as max_temp, count(h.id) as counter')
            ->getQuery()
            ->getArrayResult();

        $city = $this->createQueryBuilder('h')
            ->select('h.city, count(h.city) AS occurrence')
            ->groupBy('h.city')
            ->orderBy('occurrence', 'DESC')
            ->getQuery()
            ->getArrayResult();

        $stats[0]['most_common_city'] = $city[0]['city'];
        return $stats;
    }

}
