<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = Organization::all();
        $activities = Activity::all();

        foreach ($organizations as $organization) {
            $organization->activities()->attach(
                $activities->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
