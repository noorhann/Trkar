<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;

use App;

use App\Http\Controllers\System\SystemController;

use App\Models\Order;


class SalesReportController extends SystemController
{
    public function index()
    {
        abort(403);
    }

    public function create()
    {
        abort(403);
    }

    public function store()
    {
        abort(403);
    }
    public function edit()
    {
        abort(403);
    }


    public function update()
    {
        abort(403);
    }

    public function show(Request $request, $AttributeTyre)
    {

        $eloquentData = Order::select([
            \DB::raw('COUNT(order_details.product_id) as count'),
            'order_details.product_id'
        ])
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('type', 1);
        dd($eloquentData->get());
        if ($request->created_at1 && $request->created_at2) {
            $eloquentData->whereBetween('created_at', [
                $request->created_at1 . ' 00:00:00',
                $request->created_at2 . ' 23:59:59'
            ]);
        }
        if ($request->order_status_id) {
            $eloquentData->where('order_status_id', $request->status);
        }
        if ($request->name) {

            $eloquentData->where(function ($query) use ($request) {
                $query->where('grand_total', 'LIKE', '%' . $request->name . '%')
                    ->orWhereHas('shippingAddress', function ($q) use ($request) {
                        $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                            ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                    })
                    ->orWhereHas('orderStatus', function ($q) use ($request) {
                        $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                            ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                    });
            });
        }
        $eloquentData->orderBy('id', 'DESC');
        $eloquentData->gro('id', 'DESC');


        return $this->view('attribute_tyre.show', $this->viewData);
    }
    public function destroy()
    {
        abort(403);
    }
}
