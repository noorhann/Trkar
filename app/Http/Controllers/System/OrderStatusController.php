<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App;
use App\Helpers\Helper;
use App\Http\Requests\OrderStatusFormRequest;
use App\Models\Category;
use App\Models\OrderStatus;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class OrderStatusController extends SystemController
{
    public function index(OrderStatusFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  OrderStatus::select([
                'id',
                'name_en',
                'name_ar',
                'status',
                'color',
                'created_at'
            ]);
            if ($request->created_at1 && $request->created_at2) {
                $eloquentData->whereBetween('created_at', [
                    $request->created_at1 . ' 00:00:00',
                    $request->created_at2 . ' 23:59:59'
                ]);
            }

            if ($request->status == 2) {
                $status = 0;
                $eloquentData->where('status', $status);
            } elseif ($request->status == 1) {
                $eloquentData->where('status', $request->status);
            }
            if ($request->name) {
                $eloquentData->where(function ($query) use ($request) {
                    $query->where('name_ar', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Status'),
                    __('Action')

                ])
                
                ->addColumn('name_ar', function ($data) {
                
                    return '<span style=" color:'.$data->color.'" >' . $data->name_ar . '</span>';
                })
                ->addColumn('name_en', function ($data) {
                
                    return '<span style=" color:'.$data->color.'" >' . $data->name_en . '</span>';
                })

                ->addColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return '<span class="badge badge-soft-success">' . __(' Active') . '</span>';
                    }

                    return '<span class="badge badge-soft-danger">' . __(' In-Active') . '</span>';
                })
       
                ->addColumn('action', function ($data) {
                    $edit = '';
                    if (Helper::adminCan('admin.order-status.edit')) {
                        $edit = '<a href="' . route('admin.order-status.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    return $edit;
               
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Order Statuss')
            ];

            $this->viewData['pageTitle'] = __('Order Statuss');

            return $this->view('order_status.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Order Status'),
            'url' => route('admin.order-status.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Order Status'),
        ];

        $this->viewData['pageTitle'] = __('Create Order Status');

        return $this->view('order_status.create', $this->viewData);
    }

    public function store(OrderStatusFormRequest $request)
    {
       
        $requestData = $request->all();

        $insertData = OrderStatus::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.order-status.create')
                ]
            );
        } else {
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to add data')
            );
        }
    }
    public function edit(OrderStatus $orderStatus)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Order Status'),
            'url' => route('admin.order-status.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $orderStatus->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Order Status');
        $this->viewData['result'] = $orderStatus;

        return $this->view('order_status.create', $this->viewData);
    }


    public function update(OrderStatusFormRequest $request, OrderStatus $orderStatus)
    {
      
        $requestData = $request->all();
        $updateData = $orderStatus->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.order-status.index')
                ]
            );
        } else {
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to modify the data')
            );
        }
    }

    public function show()
    {
        abort(404);
    }
    public function destroy(OrderStatus $orderStatus)
    {
        $orderStatus->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.order-status.index')
            ]
        );
    }
}
