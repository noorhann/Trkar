<?php

namespace Database\Seeders;

use App\Models\StoreType;
use Illuminate\Database\Seeder;

class StoreTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreType::create ([
            'id'=>1,
            'name_en'=>'Retail',
            'name_ar'=>'قطاعي',
            'status'=>'1',
        ]);

        StoreType::create ([
            'id'=>2,
            'name_en'=>'Wholesale',
            'name_ar'=>'جملة',
            'status'=>'1',

        ]);

        StoreType::create ([
            'id'=>3,
            'name_en'=>'Both',
            'name_ar'=>'جملة وقطاعي',
            'status'=>'1',

        ]);
    }
}
