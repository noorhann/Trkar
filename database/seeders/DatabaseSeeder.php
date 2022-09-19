<?php

namespace Database\Seeders;

use App\Models\StoreType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            
    
            //CountrySeeder::class,
            //CitySeeder::class,
            //AreaSeeder::class,
            //RoleSeeder::class,
            //SeasonSeeder::class,
            //AttributeSeeder::class,
            //TyreTypeSeeder::class,
            //AdminSeeder::class,
            //StoreTypesSeeder::class,
            //ProductTypeSeeder::class,
            //SettingSeeder::class,
            //OrderStatusSeeder::class,
            //PaymentMethodSeeder::class,
            //ShippingCompaniesSeeder::class,
            PageSeeder::class,

        ]);
    }
}
