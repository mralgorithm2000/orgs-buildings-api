<?php

namespace App\Repositories\Eloquent;

use App\Models\Activity;
use App\Models\Organization;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    private $Organization;
    public function __construct()
    {
        $this->Organization = Organization::query();
        $this->Organization = $this->organizationDetails();
    }
    public function findById($id)
    {
        return $this->Organization->where('organizations.id',$id)->first();
    }

    public function findByName($name, $page_limit = 20)
    {
        return $this->Organization->where('name', 'LIKE', "%{$name}%")->paginate($page_limit);
    }

    public function getByBuilding($buildingId, $pageLimit = 20)
    {
        return $this->Organization->where('building_id', $buildingId)->paginate($pageLimit);
    }

    public function getByActivity($activityId, $pageLimit = 20)
    {
        return $this->Organization->whereHas('activities', function ($query) use ($activityId) {
            $query->where('activities.id', $activityId);
        })->paginate($pageLimit);
    }

    public function getByActivityTree($activityId, $pageLimit = 20)
    {
        $activityIds = $this->getActivityIds($activityId);
        $activityIds[] = $activityId;
        return $this->Organization->whereHas('activities', function ($query) use ($activityIds) {
            $query->whereIn('activities.id', $activityIds);
        })->paginate($pageLimit);
    }

    public function getNearby($latitude, $longitude, $type, $radius, $area, $pageLimit = 20)
    {
        $query = $this->Organization;

        if ($type === 'radius') {
            // Filter by radius using the Haversine formula
            $query->whereHas('building', function ($query) use ($latitude, $longitude, $radius) {
                $query->select(DB::raw('*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance'))
                    ->addBinding([$latitude, $longitude, $latitude], 'select')
                    ->having('distance', '<=', $radius)
                    ->orderBy('distance');
            });
        } elseif ($type === 'rectangle') {
            // Filter by rectangular area
            $query->whereHas('building', function ($query) use ($area) {
                $query->whereBetween('latitude', [$area[0], $area[1]])
                    ->whereBetween('longitude', [$area[2], $area[3]]);
            });
        }

        return $query->paginate($pageLimit);
    }

    private function getActivityIds($activityId, $maxLevels = 3, $currentLevel = 1)
    {
        if ($currentLevel > $maxLevels) {
            return [];
        }

        $activity = Activity::where('id', $activityId)->first();

        if ($activity == '') {
            return [];
        }

        $subcategories = Activity::where('parent_id', $activityId)
            ->pluck('id')
            ->toArray();

        $allSubcategories = [];
        foreach ($subcategories as $subcategoryId) {
            $allSubcategories[] = $subcategoryId;
            $allSubcategories = array_merge(
                $allSubcategories,
                $this->getActivityIds($subcategoryId, $maxLevels, $currentLevel + 1)
            );
        }

        return $allSubcategories;    
    }

    private function organizationDetails()
    {
        return $this->Organization->with([
            'activities' => function ($query) {
                $query->select('activities.id', 'name');
            },
            'phones' => function ($query) {
                $query->select('organization_phones.id','organization_id', 'phone_number');
            },
            'building' => function ($query) {
                $query->select('buildings.id', 'address', 'latitude', 'longitude');
            }
        ])
        ->select('organizations.id', 'name','building_id');
    }
}
