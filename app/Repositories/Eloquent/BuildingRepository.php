<?php

namespace App\Repositories\Eloquent;

use App\Models\Building;
use App\Repositories\Contracts\BuildingRepositoryInterface;

class BuildingRepository implements BuildingRepositoryInterface
{
    private $Building;
    public function __construct()
    {
        $this->Building = Building::query();
        $this->Building = $this->BuildingDetails();
    }
    public function getAll($pageLimit = 20)
    {
        return $this->Building->paginate($pageLimit);
    }

    private function BuildingDetails(){
        return $this->Building->with([
            'organizations' => function ($query) {
                $query->select('organizations.id', 'name','building_id');
            },
        ])
        ->select('buildings.id', 'address','latitude','longitude');
    }
}