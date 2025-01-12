<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\OrganizationActivity;
use App\Models\OrganizationPhone;
use Database\Seeders\ActivitySeeder;
use Database\Seeders\BuildingSeeder;
use Database\Seeders\OrganizationActivitySeeder;
use Database\Seeders\OrganizationPhoneSeeder;
use Database\Seeders\OrganizationSeeder;
use Database\Seeders\SpecificOrganizations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrganizationApiTest extends TestCase
{
    use RefreshDatabase;
    public function test_api_key_missing()
    {
        $response = $this->getJson('/api/buildings');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthorized: Invalid API Key'
            ]);
    }

    public function test_pages_limit_should_return_error_when_less_than_one()
    {
        $building = Building::factory()->create();

        $response = $this->withHeaders([
            'X-API-KEY' => env('STATIC_API_KEY'),
        ])->json("GET", "/api/organizations/building/{$building->id}", [
            'page_limit' => 0,
        ]);

        $response->assertStatus(422);

        // Check if the error message in the response is correct
        $response->assertJson([
            'message' => 'The page limit field must be at least 1.',
        ]);
    }

    public function test_pages_limit_should_return_error_when_more_than_100()
    {
        $building = Building::factory()->create();

        $response = $this->withHeaders([
            'X-API-KEY' => env('STATIC_API_KEY'),
        ])->json("GET", "/api/organizations/building/{$building->id}", [
            'page_limit' => 101,
        ]);

        $response->assertStatus(422);

        // Check if the error message in the response is correct
        $response->assertJson([
            'message' => 'The page limit field must not be greater than 100.',
        ]);
    }

    public function test_get_organizations_by_building_id()
    {
        // Create a building
        $building = Building::factory()->create();

        // Create an organization with relations
        $organization = Organization::factory()->create([
            'building_id' => $building->id,
        ]);

        $activities = Activity::factory()->count(2)->create();
        // Create activities and associate them with the organization via OrganizationActivity
        $activities = Activity::factory()->count(2)->create();
        foreach ($activities as $activity) {
            OrganizationActivity::create([
                'organization_id' => $organization->id,
                'activity_id' => $activity->id,
            ]);
        }

        $phones = OrganizationPhone::factory()->count(2)->create([
            'organization_id' => $organization->id,
        ]);

        // Add X-API-KEY header and make API requests
        $response = $this->withHeaders([
            'X-API-KEY' => env('STATIC_API_KEY'),
        ])->json("GET", "/api/organizations/building/{$building->id}");


        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the `data` structure matches expectations
        $response->assertJsonStructure($this->dataStructure());
    }

    public function test_nearby_buildings_api()
    {
        $this->seed(SpecificOrganizations::class);

        $latitude = 55.7525229;
        $longitude = 37.6205119;

        // Send the API request
        $response = $this->withHeaders([
            'X-API-KEY' => env('STATIC_API_KEY'),
        ])->json("GET", "/api/organizations/search/nearby", [
            'lat' => $latitude,
            'long' => $longitude,
            'type' => 'radius',
            'radius' => 1,
        ]);

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert that only the nearby building is included in the `data`
        $response->assertJsonStructure($this->dataStructure());
    }

    public function test_get_buildings()
    {
        $this->seed(BuildingSeeder::class);

        // Send the API request
        $response = $this->withHeaders([
            'X-API-KEY' => env('STATIC_API_KEY'),
        ])->getJson('/api/buildings');

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'address',
                    'latitude',
                    'longitude',
                    'organizations' => [
                        '*' => [
                            'id',
                            'name',
                            'building_id',
                        ],
                    ],
                ],
            ]
        ]);
    }

    public function test_organization_details()
    {
        // Run the seeder to populate test data
        $Building = Building::factory()->create();
        $Organization = Organization::factory()->create([
            'building_id' => $Building->id
        ]);
        // Send the API request
        $response = $this->withHeaders([
            'X-API-KEY' => env('STATIC_API_KEY'),
        ])->getJson('/api/organizations/' . $Organization->id);

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert the JSON structure
        $response->assertJsonStructure([
            'id',
            'name',
            'building_id',
            'activities' => [
                '*' => [
                    'id',
                    'name',
                    'pivot' => [
                        'organization_id',
                        'activity_id',
                    ],
                ],
            ],
            'phones' => [
                '*' => [
                    'id',
                    'organization_id',
                    'phone_number',
                ],
            ],
            'building' => [
                'id',
                'address',
                'latitude',
                'longitude',
            ],
        ]);
    }

    public function test_search_organization_by_name()
    {
        // Arrange: Seed the database with a known organization
        Organization::factory()->create([
            'name' => 'Test Organization',
        ]);

        // Act: Send a GET request to the search endpoint
        $response = $this->withHeaders([
            'X-API-KEY' => env('STATIC_API_KEY'), // If required by your API
        ])->get('/api/organizations/search/name?name=Test');
        // Assert: Check the response structure and content
        $response->assertStatus(200)
            ->assertJsonStructure($this->dataStructure())
            ->assertJsonFragment([
                'name' => 'Test Organization',
            ]);
    }


    public function test_activity_tree_endpoint()
    {
        // Arrange: Run the seeder to populate the database
        $this->seed(BuildingSeeder::class);
        $this->seed(ActivitySeeder::class);
        $this->seed(OrganizationSeeder::class);
        $this->seed(OrganizationPhoneSeeder::class);
        $this->seed(OrganizationActivitySeeder::class);

        // Act: Send GET request to the activity tree endpoint
        $response = $this->withHeaders([
            'X-API-KEY' => env('STATIC_API_KEY'), // If required by your API
        ])->getJson('/api/organizations/activity-tree/1');

        // Assert: Check the response structure and content
        $response->assertStatus(200)
            ->assertJsonStructure($this->dataStructure());
    }

    private function dataStructure(){
        return [
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'building_id',
                    'activities' => [
                        '*' => [
                            'id',
                            'name',
                            'pivot' => [
                                'organization_id',
                                'activity_id',
                            ],
                        ],
                    ],
                    'phones' => [
                        '*' => [
                            'id',
                            'organization_id',
                            'phone_number',
                        ],
                    ],
                    'building' => [
                        'id',
                        'address',
                        'latitude',
                        'longitude',
                    ],
                ],
            ],
        ];
    }
}
