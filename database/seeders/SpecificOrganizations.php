<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecificOrganizations extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = [
            [ // Exact location (Center)
                'addr' => 'Exact location (Center)',
                'lat' => '55.7525229',
                'long' => '37.6205119'
            ],
            
            [ // Within 1 km
                'addr' => 'Within 1 km',
                'lat' => '55.7530',
                'long' => '37.6210'
            ],
            
            [ // Within 2 km
                'addr' => 'Within 2 km',
                'lat' => '55.7545', 
                'long' => '37.6350'
            ],
            
            [ // Within 3 km
                'addr' => 'Within 3 km',
                'lat' => '55.7580',
                'long' => '37.6400'
            ],
            
            [ // Within 5 km
                'addr' => 'Within 5 km',
                'lat' => '55.7800',  
                'long' => '37.6500'
            ],
            
            [ // Outside 1 km, inside rectangle
                'addr' => 'Outside 1 km, inside rectangle',
                'lat' => '55.7425',   
                'long' => '37.6105'
            ],
        ];

        foreach($buildings as $b){
            $Building = Building::factory()->create([
                'address' => $b['addr'],
                'latitude' => $b['lat'],
                'longitude' => $b['long']
            ]);

            Organization::factory()->create([
                'building_id' => $Building->id
            ]);
        }
        
    }
}
