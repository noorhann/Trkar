<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    
    public function run()
    {
        Attribute::create ([
            'id'=>1,
            'name'=>'width',
    
        ]);
        Attribute::create ([
            'id'=>2,
            'name'=>'hieght',
    
        ]);
        Attribute::create ([
            'id'=>3,
            'name'=>'diamter',
    
        ]);
        Attribute::create ([
            'id'=>4,
            'name'=>'spead_rating',
    
        ]);
        Attribute::create ([
            'id'=>5,
            'name'=>'load_index',
    
        ]);
        Attribute::create ([
            'id'=>6,
            'name'=>'axle',
    
        ]);
        Attribute::create ([
            'id'=>7,
            'name'=>'Manufacturer',
        ]);
    }
}
