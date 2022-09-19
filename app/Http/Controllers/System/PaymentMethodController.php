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
use App\Http\Requests\PaymentMethodFormRequest;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class PaymentMethodController extends SystemController
{
    public function index(PaymentMethodFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  PaymentMethod::select([
                'id',
                'name_en',
                'name_ar',
                'status',
                'image',
                'slug',
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
                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('slug', 'LIKE', '%' . $request->name . '%');
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Slug'),
                    __('Image'),
                    __('Status'),
                    __('Action')

                ])
                
                ->addColumn('name_ar')
                ->addColumn('name_en')
                ->addColumn('slug')
                ->addColumn('image', function ($data) {
                    if ($data->image == null) {
                        return '--';
                    }

                    return '<img src="' . Helper::path() . '/' .  $data->image . '" style="width: 40px; height: 40px;" />';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return '<span class="badge badge-soft-success">' . __(' Active') . '</span>';
                    }

                    return '<span class="badge badge-soft-danger">' . __(' In-Active') . '</span>';
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.payment-method.edit')) {
                        $edit = '<a href="' . route('admin.payment-method.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.payment-method.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.payment-method.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
               
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Payment Methods')
            ];

            $this->viewData['pageTitle'] = __('Payment Methods');

            return $this->view('payment_method.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Payment Method'),
            'url' => route('admin.payment-method.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Payment Method'),
        ];

        $this->viewData['pageTitle'] = __('Create Payment Method');

        return $this->view('payment_method.create', $this->viewData);
    }

    public function store(PaymentMethodFormRequest $request)
    {
       
        $requestData = $request->all();
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("store",  $request->file('image'));
        }
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();

        $insertData = PaymentMethod::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.payment-method.create')
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
    public function edit(PaymentMethod $paymentMethod)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Payment Method'),
            'url' => route('admin.payment-method.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $paymentMethod->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Payment Method');
        $this->viewData['result'] = $paymentMethod;

        return $this->view('payment_method.create', $this->viewData);
    }


    public function update(PaymentMethodFormRequest $request, PaymentMethod $paymentMethod)
    {
      
        $requestData = $request->all();
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("payment",  $request->file('image'));
        } else {
            $requestData['image'] =  $paymentMethod->image;
        }
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();
        $updateData = $paymentMethod->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.payment-method.index')
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
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.payment-method.index')
            ]
        );
    }
}
