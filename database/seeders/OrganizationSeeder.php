<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = Building::all();
        Organization::factory()
            ->count(20)
            ->create()
            ->each(function ($organization) use ($buildings) {
                $organization->update([
                    'building_id' => $buildings->random()->id,
                ]);
            });
    }
}
