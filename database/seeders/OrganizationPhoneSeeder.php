<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationPhone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationPhoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = Organization::all();

        foreach ($organizations as $organization) {
            OrganizationPhone::factory()
                ->count(rand(1, 3))
                ->create(['organization_id' => $organization->id]);
        }
    }
}
