<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create ([
            'id'=>1,
            'name_en'=>'Fawry',
            'name_ar'=>'فوري',
            'slug'=>Str::slug('Fawry'),
            'status'=>1,

        ]);
        PaymentMethod::create ([
            'id'=>2,
            'name_en'=>'Paymob',
            'name_ar'=>'Paymob',
            'slug'=>Str::slug('Paymob'),
            'status'=>1,

        ]);
    }
}
