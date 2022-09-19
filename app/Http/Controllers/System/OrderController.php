<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
// use App;
use App\Helpers\Helper;
use App\Models\OrderAttribute;
use App\Models\OrderDetails;
use App\Models\OrderImage;
use App\Models\OrderTag;
use App\Models\UserAddress;
use Illuminate\Support\Facades\App;

class OrderController extends SystemController
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
                'created_at'
            ])->where('type', 1);
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
                    __('Discount'),
                    __('Date'),
                    __('Count Product'),
                    __('Shipping Address'),
                    __('Order Status'),
                    __('Paying Off Status'),
                    __('Action')

                ])

                ->addColumn('order_number')
                ->addColumn('grand_total')
                ->addColumn('discount')
                ->addColumn('date')
              
                ->addColumn('orderDetails', function ($data) {
                    if (isset($data->orderDetails)) {
                        return count($data->orderDetails);
                    } else {
                        return '--';
                    }
                })
              
                ->addColumn('shippingAddress', function ($data) {
                    if (isset($data->shippingAddress)) {
                        return $data->shippingAddress->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('orderStatus', function ($data) {
                    if (isset($data->orderStatus)) {
                        return '<span style=" color:'.$data->orderStatus->color.'" >' . $data->orderStatus->name_ar . '</span>';
                    } else {
                        return '--';
                    }
                })
                ->addColumn('paying_off', function ($data) {
                        if ($data->paying_off == 1) {
                            return '<span class="badge badge-soft-success">'.__('Success').'</span>';
                        } elseif ($data->paying_off == 2) {
                            return '<span class="badge badge-soft-warning">'.__('Faild').'</span>';
                        } elseif ($data->paying_off == 3) {
                            return '<span class="badge badge-soft-info">'.__('Pending').'</span>';
                        }
               
                })
                ->addColumn('action', function ($data) {
                    $show = '';

                    if (Helper::adminCan('admin.order.show')) {
                        $show = ' <a href="' . route('admin.order.show', $data->id) . '" class="action-icon"> <i class="mdi mdi-eye-circle-outline"></i></a>';
                    }
                    return  $show;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Orders')
            ];

            $this->viewData['pageTitle'] = __('Orders');

            return $this->view('order.index', $this->viewData);
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

    public function show(Order $order)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Orders'),
            'url' => route('admin.order.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Show (:name)', ['name' => $order->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Show Order');
        $userAddresses = UserAddress::where('user_id',$order->user_id)->where('default',1)->first();
        $this->viewData['result'] = $order;
        $this->viewData['userAddresses'] = $userAddresses;

        return $this->view('order.show', $this->viewData);
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
                'url' => route('admin.order.index')
            ]
        );
    }
}
