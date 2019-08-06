<?php

namespace App\Services;

use App\Entity\History;
use App\Repository\HistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class HistoryService  extends Service
{

    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        HistoryRepository $historyRepository,
        ContainerInterface $container
    ) {
        parent::__construct($container);

        $this->entityManager = $entityManager;
        $this->historyRepository = $historyRepository;
      
    }

    public function save(array $data): void
    {
        $history = new History();
        $history->setLng($data['coord']['lon']);
        $history->setLat($data['coord']['lat']);
        $history->setTemp($data['main']['temp']);
        $history->setClouds($data['clouds']['all']);
        $history->setWind($data['wind']['speed']);
        $history->setDescription($data['weather'][0]['description']);
        $history->setCity($data['name']);
        $history->setTime($data['time']);

        $this->entityManager->persist($history);
        $this->entityManager->flush();
    }

    public function get(ParameterBag $parameterBag)
    {
        $queryBuilder = $this->historyRepository->getAllQuery();
        return $this->addPaginator($parameterBag, $queryBuilder->getQuery());
    }

    public function getStats(): array
    {
        return $this->historyRepository->getStatistics()[0];
    }

}
