<?php
namespace App\Repositories\Contracts;

interface BuildingRepositoryInterface
{
    public function getAll($pageLimit = 20);
}