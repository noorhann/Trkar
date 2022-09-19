<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductType::create ([
            'id'=>1,
            'name_en'=>'Retail',
            'name_ar'=>'قطاعي',
            'status'=>'1',
        ]);

        ProductType::create ([
            'id'=>2,
            'name_en'=>'Wholesale',
            'name_ar'=>'جملة',
            'status'=>'1',

        ]);

    }
}
