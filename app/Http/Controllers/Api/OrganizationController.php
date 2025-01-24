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
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Продуктивный сервер"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="apiKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY",
 *     description="API key for authentication"
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
     *     summary="Get organization details by its ID",
     *     tags={"Organizations"},
     *     security={{"apiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Organization details returned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Schmitt-Schneider"),
     *                 @OA\Property(property="building_id", type="integer", example=3),
     *                 @OA\Property(
     *                     property="activities",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         properties={
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Еда"),
     *                             @OA\Property(
     *                                 property="pivot",
     *                                 type="object",
     *                                 properties={
     *                                     @OA\Property(property="organization_id", type="integer", example=1),
     *                                     @OA\Property(property="activity_id", type="integer", example=1)
     *                                 }
     *                             )
     *                         }
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="phones",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         properties={
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="organization_id", type="integer", example=1),
     *                             @OA\Property(property="phone_number", type="string", example="+1-386-646-3674")
     *                         }
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="building",
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="address", type="string", example="104 Assunta Stravenue Suite 303\nEfrenport, WA 73410-0252"),
     *                         @OA\Property(property="latitude", type="string", example="17.9043520"),
     *                         @OA\Property(property="longitude", type="string", example="132.7195980")
     *                     }
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Organization not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Organization not found")
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
     *     summary="List of all organizations located in a specific building",
     *     tags={"Organizations"},
     *     security={{"apiKey": {}}},
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
     *         description="List of organizations in the specified building",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(
     *                     property="current_page",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="first_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/organizations/building/2?page=1"
     *                 ),
     *                 @OA\Property(
     *                     property="last_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/organizations/building/2?page=2"
     *                 ),
     *                 @OA\Property(
     *                     property="next_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/organizations/building/2?page=2"
     *                 ),
     *                 @OA\Property(
     *                     property="prev_page_url",
     *                     type="string",
     *                     example="null"
     *                 ),
     *                 @OA\Property(
     *                     property="per_page",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     type="integer",
     *                     example=2
     *                 ),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         properties={
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="DuBuque PLC"),
     *                             @OA\Property(property="building_id", type="integer", example=2),
     *                             @OA\Property(
     *                                 property="activities",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     properties={
     *                                         @OA\Property(property="id", type="integer", example=1),
     *                                         @OA\Property(property="name", type="string", example="Еда"),
     *                                         @OA\Property(
     *                                             property="pivot",
     *                                             type="object",
     *                                             properties={
     *                                                 @OA\Property(property="organization_id", type="integer", example=2),
     *                                                 @OA\Property(property="activity_id", type="integer", example=1)
     *                                             }
     *                                         )
     *                                     }
     *                                 )
     *                             ),
     *                             @OA\Property(
     *                                 property="phones",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     properties={
     *                                         @OA\Property(property="id", type="integer", example=3),
     *                                         @OA\Property(property="phone_number", type="string", example="+1-781-732-9856")
     *                                     }
     *                                 )
     *                             ),
     *                             @OA\Property(
     *                                 property="building",
     *                                 type="object",
     *                                 properties={
     *                                     @OA\Property(property="id", type="integer", example=2),
     *                                     @OA\Property(property="address", type="string", example="877 Cathrine Port\nLake Rodland, ME 52865"),
     *                                     @OA\Property(property="latitude", type="string", example="-82.5707030"),
     *                                     @OA\Property(property="longitude", type="string", example="146.9179510")
     *                                 }
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
     *     summary="List of all organizations related to the specified activity",
     *     tags={"Organizations"},
     *     security={{"apiKey": {}}},
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
     *         description="List of organizations related to the specified activity",
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
     *                     example="http://localhost:8000/api/organizations/activity/3?page=1"
     *                 ),
     *                 @OA\Property(
     *                     property="last_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/organizations/activity/3?page=6"
     *                 ),
     *                 @OA\Property(
     *                     property="next_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/organizations/activity/3?page=4"
     *                 ),
     *                 @OA\Property(
     *                     property="prev_page_url",
     *                     type="string",
     *                     example="http://localhost:8000/api/organizations/activity/3?page=2"
     *                 ),
     *                 @OA\Property(
     *                     property="per_page",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     type="integer",
     *                     example=6
     *                 ),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         properties={
     *                             @OA\Property(property="id", type="integer", example=12),
     *                             @OA\Property(property="name", type="string", example="Howe Inc"),
     *                             @OA\Property(property="building_id", type="integer", example=9),
     *                             @OA\Property(
     *                                 property="activities",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     properties={
     *                                         @OA\Property(property="id", type="integer", example=2),
     *                                         @OA\Property(property="name", type="string", example="Мясная продукция"),
     *                                         @OA\Property(
     *                                             property="pivot",
     *                                             type="object",
     *                                             properties={
     *                                                 @OA\Property(property="organization_id", type="integer", example=12),
     *                                                 @OA\Property(property="activity_id", type="integer", example=2)
     *                                             }
     *                                         )
     *                                     }
     *                                 )
     *                             ),
     *                             @OA\Property(
     *                                 property="phones",
     *                                 type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     properties={
     *                                         @OA\Property(property="id", type="integer", example=25),
     *                                         @OA\Property(property="phone_number", type="string", example="+1 (763) 309-3017")
     *                                     }
     *                                 )
     *                             ),
     *                             @OA\Property(
     *                                 property="building",
     *                                 type="object",
     *                                 properties={
     *                                     @OA\Property(property="id", type="integer", example=9),
     *                                     @OA\Property(property="address", type="string", example="280 White Estate Suite 584\nLangoshland, AL 65860"),
     *                                     @OA\Property(property="latitude", type="string", example="21.6551890"),
     *                                     @OA\Property(property="longitude", type="string", example="-69.0083690")
     *                                 }
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
     *     security={{"apiKey": {}}},
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
     *         description="List of organizations related to the specified activity and its subcategories up to 3 levels.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Schmitt-Schneider"),
     *                     @OA\Property(property="building_id", type="integer", example=3),
     *                     @OA\Property(
     *                         property="activities",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Еда"),
     *                             @OA\Property(
     *                                 property="pivot",
     *                                 type="object",
     *                                 @OA\Property(property="organization_id", type="integer", example=1),
     *                                 @OA\Property(property="activity_id", type="integer", example=1)
     *                             )
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="phones",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="organization_id", type="integer", example=1),
     *                             @OA\Property(property="phone_number", type="string", example="+1-386-646-3674")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="building",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="address", type="string", example="104 Assunta Stravenue Suite 303\nEfrenport, WA 73410-0252"),
     *                         @OA\Property(property="latitude", type="string", example="17.9043520"),
     *                         @OA\Property(property="longitude", type="string", example="132.7195980")
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/organizations/activity-tree/1?page=1"),
     *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/organizations/activity-tree/1?page=1"),
     *             @OA\Property(property="next_page_url", type="string", example="null"),
     *             @OA\Property(property="prev_page_url", type="string", example="null"),
     *             @OA\Property(property="total", type="integer", example=12)
     *         )
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
     *     summary="Search for an organization by name",
     *     tags={"Organizations"},
     *     security={{"apiKey": {}}},
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
     *         description="List of organizations matching the search criteria",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="DuBuque PLC"),
     *                         @OA\Property(property="building_id", type="integer", example=2),
     *                         @OA\Property(
     *                             property="activities",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 properties={
     *                                     @OA\Property(property="id", type="integer", example=1),
     *                                     @OA\Property(property="name", type="string", example="Еда"),
     *                                     @OA\Property(
     *                                         property="pivot",
     *                                         type="object",
     *                                         properties={
     *                                             @OA\Property(property="organization_id", type="integer", example=2),
     *                                             @OA\Property(property="activity_id", type="integer", example=1)
     *                                         }
     *                                     )
     *                                 }
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="phones",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 properties={
     *                                     @OA\Property(property="id", type="integer", example=3),
     *                                     @OA\Property(property="organization_id", type="integer", example=2),
     *                                     @OA\Property(property="phone_number", type="string", example="+1-781-732-9856")
     *                                 }
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="building",
     *                             type="object",
     *                             properties={
     *                                 @OA\Property(property="id", type="integer", example=2),
     *                                 @OA\Property(property="address", type="string", example="877 Cathrine Port\nLake Rodland, ME 52865"),
     *                                 @OA\Property(property="latitude", type="string", example="-82.5707030"),
     *                                 @OA\Property(property="longitude", type="string", example="146.9179510")
     *                             }
     *                         )
     *                     }
     *                 )
     *             ),
     *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/organizations/search/name?page=1"),
     *             @OA\Property(property="from", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=1),
     *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/organizations/search/name?page=1"),
     *             @OA\Property(
     *                 property="links",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="url", type="string", example="http://localhost:8000/api/organizations/search/name?page=1"),
     *                         @OA\Property(property="label", type="string", example="1"),
     *                         @OA\Property(property="active", type="boolean", example=true)
     *                     }
     *                 )
     *             ),
     *             @OA\Property(property="next_page_url", type="string", example=null),
     *             @OA\Property(property="path", type="string", example="http://localhost:8000/api/organizations/search/name"),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="prev_page_url", type="string", example=null),
     *             @OA\Property(property="to", type="integer", example=2),
     *             @OA\Property(property="total", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid parameters",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invalid parameters")
     *         )
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
     *     summary="List of organizations within a specified radius/rectangular area relative to the given point on the map. List of buildings.",
     *     tags={"Organizations"},
     *     security={{"apiKey": {}}},
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
     *         description="List of organizations matching the search criteria",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="DuBuque PLC"),
     *                         @OA\Property(property="building_id", type="integer", example=2),
     *                         @OA\Property(
     *                             property="activities",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 properties={
     *                                     @OA\Property(property="id", type="integer", example=1),
     *                                     @OA\Property(property="name", type="string", example="Еда"),
     *                                     @OA\Property(
     *                                         property="pivot",
     *                                         type="object",
     *                                         properties={
     *                                             @OA\Property(property="organization_id", type="integer", example=2),
     *                                             @OA\Property(property="activity_id", type="integer", example=1)
     *                                         }
     *                                     )
     *                                 }
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="phones",
     *                             type="array",
     *                             @OA\Items(
     *                                 type="object",
     *                                 properties={
     *                                     @OA\Property(property="id", type="integer", example=3),
     *                                     @OA\Property(property="organization_id", type="integer", example=2),
     *                                     @OA\Property(property="phone_number", type="string", example="+1-781-732-9856")
     *                                 }
     *                             )
     *                         ),
     *                         @OA\Property(
     *                             property="building",
     *                             type="object",
     *                             properties={
     *                                 @OA\Property(property="id", type="integer", example=2),
     *                                 @OA\Property(property="address", type="string", example="877 Cathrine Port\nLake Rodland, ME 52865"),
     *                                 @OA\Property(property="latitude", type="string", example="-82.5707030"),
     *                                 @OA\Property(property="longitude", type="string", example="146.9179510")
     *                             }
     *                         )
     *                     }
     *                 )
     *             ),
     *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/organizations/search/name?page=1"),
     *             @OA\Property(property="from", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=1),
     *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/organizations/search/name?page=1"),
     *             @OA\Property(
     *                 property="links",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     properties={
     *                         @OA\Property(property="url", type="string", example="http://localhost:8000/api/organizations/search/name?page=1"),
     *                         @OA\Property(property="label", type="string", example="1"),
     *                         @OA\Property(property="active", type="boolean", example=true)
     *                     }
     *                 )
     *             ),
     *             @OA\Property(property="next_page_url", type="string", example=null),
     *             @OA\Property(property="path", type="string", example="http://localhost:8000/api/organizations/search/name"),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="prev_page_url", type="string", example=null),
     *             @OA\Property(property="to", type="integer", example=2),
     *             @OA\Property(property="total", type="integer", example=2)
     *         )
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
