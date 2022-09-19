<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TyreType;

class TyreTypeSeeder extends Seeder
{

    public function run()
    {
        TyreType::create ([
            'id'=>1,
            'name_en'=>'Passenger car',
            'name_ar'=>'سيارة ركوب',


    
        ]);
        TyreType::create ([
            'id'=>2,
            'name_en'=>'Off-Road/4x4/SUV',
            'name_ar'=> '4x4 / الطرق الوعرة /SUV',
    
        ]);
        TyreType::create ([
            'id'=>3,
            'name_en'=>'Light truck',
            'name_ar'=> 'شاحنة خفيفة',
    
        ]);
        TyreType::create ([
            'id'=>4,
            'name_en'=>'Truck',
            'name_ar'=> 'شاحنة',
    
        ]);
    
        
    }
}
