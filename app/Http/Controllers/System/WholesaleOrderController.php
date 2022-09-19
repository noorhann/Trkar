<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\OrderAttribute;
use App\Models\OrderDetails;
use App\Models\OrderImage;
use App\Models\OrderTag;
use App\Models\UserAddress;
use Illuminate\Support\Facades\App;

class WholesaleOrderController extends SystemController
{
    public function index(Request $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Order::select([
                'id',
                'order_number',
                'user_id',
                'discount',
                'shipping_address_id',
                'grand_total',
                'date',
                'paying_off',
                'order_status_id',
                'type',
                'created_at'
            ])->where('type', 2);
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
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Order Number'),
                    __('Grand Total'),
                    __('Date'),
                    __('Count Product'),
                    __('Order Status'),
                    __('Action')

                ])

                ->addColumn('order_number')
                ->addColumn('grand_total')
                ->addColumn('date')

                ->addColumn('orderDetails', function ($data) {
                    if (isset($data->orderDetails)) {
                        return count($data->orderDetails);
                    } else {
                        return '--';
                    }
                })

                ->addColumn('orderStatus', function ($data) {
                    if (isset($data->orderStatus)) {
                        return '<span style=" color:' . $data->orderStatus->color . '" >' . $data->orderStatus->name_ar . '</span>';
                    } else {
                        return '--';
                    }
                })
          
                ->addColumn('action', function ($data) {
                    $show = '';

                    if (Helper::adminCan('admin.wholesale-order.show')) {
                        $show = ' <a href="' . route('admin.wholesale-order.show', $data->id) . '" class="action-icon"> <i class="mdi mdi-eye-circle-outline"></i></a>';
                    }
                    return  $show;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Wholesale Orders')
            ];

            $this->viewData['pageTitle'] = __('Wholesale Orders');

            return $this->view('wholesale_order.index', $this->viewData);
        }
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }
    public function edit(Order $Order)
    {

        abort(404);
    }


    public function update(Request $request, Order $Order)
    {
        abort(404);
    }

    public function show(Order $wholesale_order)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Wholesale Orders'),
            'url' => route('admin.wholesale-order.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Show (:name)', ['name' => $wholesale_order->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Show Wholesale Order');
        $this->viewData['result'] = $wholesale_order;

        return $this->view('wholesale_order.show', $this->viewData);
    }
    public function destroy(Order $Order)
    {
        abort(404);
    }

    public function approved(Order $Order)
    {
        if ($Order->approved == 0) {
            $approved = 1;
        } elseif ($Order->approved == 1) {
            $approved = 0;
        }
        $updateData = $Order->update([
            'approved' => $approved
        ]);

        $Order->update;
        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.wholesale-order.index')
            ]
        );
    }
}
