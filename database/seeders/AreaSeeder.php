<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{

    public function run()
    {
        $path = base_path() . '/database/seeders/areas.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
