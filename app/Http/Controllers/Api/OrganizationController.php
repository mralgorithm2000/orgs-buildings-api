<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API организаций и зданий",
 *     description="API для управления организациями, зданиями и деятельностью.",
 *     @OA\Contact(
 *         email="support@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="https://api.example.com",
 *     description="Продуктивный сервер"
 * )
 * 
 * @OA\Server(
 *     url="https://api-staging.example.com",
 *     description="Сервер для тестирования"
 * )
 */
class OrganizationController extends Controller
{
    protected $organizationRepository;

    public function __construct(OrganizationRepositoryInterface $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="вывод информации об организации по её идентификатору",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Organization details returned successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     * )
     */
    public function show($id)
    {
        $organization = $this->organizationRepository->findById($id);
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        return response()->json($organization);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/building/{buildingId}",
     *     summary="список всех организаций находящихся в конкретном здании",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page_limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of organizations in the specified building"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid parameters"
     *     )
     * )
     */
    public function getByBuilding(Request $request, $buildingId)
    {
        $request->validate([
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->organizationRepository->getByBuilding($buildingId, $request->page_limit));
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/activity/{activityId}",
     *     summary="список всех организаций, которые относятся к указанному виду деятельности",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page_limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of organizations related to the specified activity"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid parameters"
     *     )
     * )
     */
    public function getByActivity(Request $request, $activityId)
    {
        $request->validate([
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->organizationRepository->getByActivity($activityId, $request->page_limit));
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/activity-tree/{activityId}",
     *     summary="искать организации по виду деятельности и его подкатегориям до 3 уровней.",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page_limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of organizations related to the specified activity and its subcategories up to 3 levels."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid parameters"
     *     )
     * )
     */
    public function getByActivityTree(Request $request, $activityId)
    {
        $request->validate([
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->organizationRepository->getByActivityTree($activityId, $request->page_limit));
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/search/name",
     *     summary="поиск организации по названию",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", minLength=2, maxLength=100)
     *     ),
     *     @OA\Parameter(
     *         name="page_limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of organizations matching the search criteria"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid parameters"
     *     )
     * )
     */
    public function searchByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:100',
            'page_limit' => 'integer|min:1|max:100'
        ]);
        return response()->json($this->organizationRepository->findByName($request->name, $request->page_limit));
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/search/nearby",
     *     summary="список организаций, которые находятся в заданном радиусе/прямоугольной области относительно указанной точки на карте. список зданий",
     *     tags={"Organizations"},
     *     @OA\Parameter(
     *         name="lat",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="long",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", enum={"radius", "rectangle"})
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="number", minimum=0),
     *         description="Radius in KM, required if type is 'radius'"
     *     ),
     *     @OA\Parameter(
     *         name="area",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="number")),
     *         description="Rectangle area defined by 4 coordinates (lat1, long1, lat2, long2), required if type is 'rectangle'",
     *         @OA\AdditionalProperties(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="page_limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of organizations in the specified area"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid parameters"
     *     )
     * )
     */
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
