<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'name' => 'Еда',
                'children' => [
                    [
                        'name' => 'Мясная продукция',
                    ],
                    [
                        'name' => 'Молочная продукция'
                    ]
                ],
            ],
            [
                'name' => 'Автомобили',
                'children' => [
                    [
                        'name' => 'Грузовые'
                    ],
                    [
                        'name' => 'Легковые',
                        'children' => [
                            [
                                'name' => 'Запчасти',
                            ],
                            [
                                'name' => 'Аксессуары'
                            ]
                        ]
                    ]
                ],
            ],
        ];

        foreach($activities as $a){
            $this->addActivity($a);
        }
        
    }

    private function addActivity($activity,$parent_id = null){
        $Activity = Activity::create([
            'name' => $activity['name'],
            'parent_id' => $parent_id
        ]);

        if(isset($activity['children'])){
            foreach($activity['children'] as $a){
                $this->addActivity($a,$Activity->id);
            }
        }
    }
}
