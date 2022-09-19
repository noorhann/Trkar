<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::create ([
            'id'=>1,
            'key'=>'About us',

        ]);

        Page::create ([
            'id'=>2,
            'key'=>'Help Сentre',

        ]);

        Page::create ([
            'id'=>3,
            'key'=>'Terms & conditions',

        ]);
        Page::create ([
            'id'=>4,
            'key'=>'Privacy policy',

        ]);
        Page::create ([
            'id'=>5,
            'key'=>'Payment',

        ]);
        Page::create ([
            'id'=>6,
            'key'=>'Delivery',

        ]);
        Page::create ([
            'id'=>7,
            'key'=>'Contact us',

        ]);
        Page::create ([
            'id'=>8,
            'key'=>'Returns & refunds',

        ]);
        
    }
}
