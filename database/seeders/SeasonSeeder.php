<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Season;
class SeasonSeeder extends Seeder
{
    
    public function run()
    {
        Season::create ([
            'name'=>'All-seasons tyres',
    
        ]);
        Season::create ([
            'name'=>'summer tyres',
    
        ]);
        Season::create ([
            'name'=>'winter tyres',
    
        ]);
    }
}
