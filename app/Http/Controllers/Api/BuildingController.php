<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BuildingRepositoryInterface;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    protected $buildingRepository;

    public function __construct(BuildingRepositoryInterface $buildingRepository)
    {
        $this->buildingRepository = $buildingRepository;
    }

    public function index(Request $request)
    {
        $request->validate([
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->buildingRepository->getAll($request->page_limit));
    }
}
