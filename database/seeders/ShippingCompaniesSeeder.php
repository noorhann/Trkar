<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\ShippingCompanies;
use Illuminate\Support\Facades\Storage;

class ShippingCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingCompanies::create ([
            'id'=>1,
            'name_en'=>'Aramex',
            'name_ar'=>'أرامكس',
            'slug'=>Str::slug('Aramex'),
            'status'=>1,
            'shipping_cost'=>'50',
            'time'=>'3-5'

        ]);
        
    }
}
