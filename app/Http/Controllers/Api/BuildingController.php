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
     *     summary="List of buildings",
     *     tags={"Buildings"},
     *     security={{"apiKey": {}}},
     *     @OA\Parameter(
     *         name="page_limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=20)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of buildings",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(
     *                     property="current_page",
     *                     type="integer",
     *                     example=3
     *                 ),
     *                 @OA\Property(
     *                     property="first_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/buildings?page=1"
     *                 ),
     *                 @OA\Property(
     *                     property="last_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/buildings?page=18"
     *                 ),
     *                 @OA\Property(
     *                     property="next_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/buildings?page=4"
     *                 ),
     *                 @OA\Property(
     *                     property="prev_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/buildings?page=2"
     *                 ),
     *                 @OA\Property(
     *                     property="per_page",
     *                     type="integer",
     *                     example=2
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     type="integer",
     *                     example=36
     *                 ),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         properties={
     *                             @OA\Property(property="id", type="integer", example=5),
     *                             @OA\Property(property="address", type="string", example="3734 Stone River Apt. 791\nNorth Savanahland, KS 75867-0148"),
     *                             @OA\Property(property="latitude", type="string", example="5.8428020"),
     *                             @OA\Property(property="longitude", type="string", example="-141.0151560"),
     *                             @OA\Property(
     *                                 property="organizations",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     properties={
     *                                         @OA\Property(property="id", type="integer", example=3),
     *                                         @OA\Property(property="name", type="string", example="Wintheiser-Lakin"),
     *                                         @OA\Property(property="building_id", type="integer", example=5)
     *                                     }
     *                                 )
     *                             )
     *                         }
     *                     )
     *                 )
     *             }
     *         )
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
