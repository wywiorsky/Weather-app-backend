<?php

namespace App\Services;

use Doctrine\ORM\Query;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class Service
{
    public $limit = 10;
    public $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addPaginator(ParameterBag $parameterBag, Query $query): array
    {

        $paginator = $this->container->get('knp_paginator');
        $data = $paginator->paginate(
            $query,
            $parameterBag->getInt('page', 1),
            $parameterBag->getInt('limit', $this->limit)

        );
        return [
            "data" => $data,
            "pages" => ceil($data->getTotalItemCount() / $data->getItemNumberPerPage()),
        ];
    }
}