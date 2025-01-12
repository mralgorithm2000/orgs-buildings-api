<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    protected $organizationRepository;

    public function __construct(OrganizationRepositoryInterface $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function show($id)
    {
        $organization = $this->organizationRepository->findById($id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        return response()->json($organization);
    }

    public function getByBuilding(Request $request,$buildingId)
    {
        $request->validate([
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->organizationRepository->getByBuilding($buildingId,$request->page_limit));
    }

    public function getByActivity(Request $request,$activityId)
    {
        $request->validate([
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->organizationRepository->getByActivity($activityId,$request->page_limit));
    }

    public function getByActivityTree(Request $request,$activityId)
    {
        $request->validate([
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->organizationRepository->getByActivityTree($activityId,$request->page_limit));
    }

    public function searchByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:100',
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->organizationRepository->findByName($request->name,$request->page_limit));
    }

    public function getNearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'type' => 'required|in:radius,rectangle',
            'radius' => 'required_if:type,radius|numeric|min:0',
            'area' => 'required_if:type,rectangle|array|size:4',
            'area.*' => 'numeric',
            'page_limit' => 'integer|min:1|max:100'
        ]);

        $latitude = $request->input('lat');
        $longitude = $request->input('long');
        $type = $request->input('type');
        $radius = $request->input('radius'); // Only used if type is 'radius'
        $area = $request->input('area');     // Only used if type is 'rectangle'

        return response()->json($this->organizationRepository->getNearby($latitude, $longitude, $type, $radius, $area, $request->page_limit));
    }
}
