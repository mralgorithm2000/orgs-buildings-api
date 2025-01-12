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

    /**
     * @OA\Get(
     *     path="/api/buildings",
     *     summary="list of buildings",
     *     tags={"Buildings"},
     *     @OA\Parameter(
     *         name="page_limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of buildings"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid parameters"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $request->validate([
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->buildingRepository->getAll($request->page_limit));
    }
}
