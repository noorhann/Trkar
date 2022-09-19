<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create ([
            'username'=>'Admin',
            'email'=>'admin@admin.com',
            'email_verified_at'=>Carbon::now(),
            'password'=>bcrypt('12345678'),
            'status'=>'1',
            'uuid'=>'A001',

        ]);
    }
}
