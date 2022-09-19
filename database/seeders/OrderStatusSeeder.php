<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::create ([
            'id'=>1,
            'name_en'=>'Completed',
            'name_ar'=>'مكتمل',
            'status'=>1,

        ]);
        OrderStatus::create ([
            'id'=>2,
            'name_en'=>'Pending',
            'name_ar'=>'قيد الانتظار',
            'status'=>1,

        ]);
        OrderStatus::create ([
            'id'=>3,
            'name_en'=>'Processing',
            'name_ar'=>'قيد التجهيز',
            'status'=>1,

        ]);
        OrderStatus::create ([
            'id'=>4,
            'name_en'=>'Shipped',
            'name_ar'=>'تم الشحن',
            'status'=>1,

        ]);
        OrderStatus::create ([
            'id'=>5,
            'name_en'=>'Cancelled',
            'name_ar'=>'تم الإلغاء',
            'status'=>1,

        ]);
        OrderStatus::create ([
            'id'=>6,
            'name_en'=>'retrieved',
            'name_ar'=>'تم الاسترجاع ',
            'status'=>1,

        ]);
    }
}
