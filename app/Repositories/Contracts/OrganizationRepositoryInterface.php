<?php
namespace App\Repositories\Contracts;

interface OrganizationRepositoryInterface
{
    public function findById($id);
    public function findByName($name,$page_limit = 20);
    public function getByBuilding($buildingId,$pageLimit = 20);
    public function getByActivity($activityId, $pageLimit = 20);
    public function getByActivityTree($activityId, $pageLimit = 20);
    public function getNearby($latitude, $longitude, $type, $radius, $area, $pageLimit = 20);
}