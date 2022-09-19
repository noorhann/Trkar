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
use App\Http\Requests\ShippingCompanyFormRequest;
use App\Models\Category;
use App\Models\ShippingCompany;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class ShippingCompanyController extends SystemController
{
    public function index(ShippingCompanyFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  ShippingCompany::select([
                'id',
                'name_en',
                'slug',
                'name_ar',
                'status',
                'logo',
                'shipping_cost',
                'time',
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
                        ->orWhere('shipping_cost', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('time', 'LIKE', '%' . $request->name . '%')
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
                    __('Shipping Cost'),
                    __('Time'),
                    __('Status'),
                    __('Logo'),
                    __('Action')

                ])
                
                ->addColumn('name_ar')
                ->addColumn('name_en')
                ->addColumn('slug')
                ->addColumn('shipping_cost')
                ->addColumn('time')

                ->addColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return '<span class="badge badge-soft-success">' . __(' Active') . '</span>';
                    }

                    return '<span class="badge badge-soft-danger">' . __(' In-Active') . '</span>';
                })
                ->addColumn('logo', function ($data) {
                    if ($data->logo == null) {
                        return '--';
                    }

                    return '<img src="' . Helper::path() . '/' .  $data->logo . '" style="width: 40px; height: 40px;" />';
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.shipping-company.edit')) {
                        $edit = '<a href="' . route('admin.shipping-company.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.shipping-company.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.shipping-company.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
               
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Shipping Companies')
            ];

            $this->viewData['pageTitle'] = __('Shipping Companies');

            return $this->view('shipping_company.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Shipping Company'),
            'url' => route('admin.shipping-company.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Shipping Company'),
        ];

        $this->viewData['pageTitle'] = __('Create Shipping Company');

        return $this->view('shipping_company.create', $this->viewData);
    }

    public function store(ShippingCompanyFormRequest $request)
    {
       
        $requestData = $request->all();
        $requestData['status'] = 1;
        if ($request->file('logo')) {
            $requestData['logo'] =  Storage::disk('public')->put("ShippingCompanies",  $request->file('logo'));
        }
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();

        $insertData = ShippingCompany::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.shipping-company.create')
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
    public function edit(ShippingCompany $ShippingCompany)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Shipping Company'),
            'url' => route('admin.shipping-company.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $ShippingCompany->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Shipping Company');
        $this->viewData['result'] = $ShippingCompany;

        return $this->view('shipping_company.create', $this->viewData);
    }


    public function update(ShippingCompanyFormRequest $request, ShippingCompany $ShippingCompany)
    {
      
        $requestData = $request->all();
        if ($request->file('logo')) {
            $requestData['logo'] =  Storage::disk('public')->put("categories",  $request->file('logo'));
        } else {
            $requestData['logo'] =  $ShippingCompany->logo;
        }
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();
        $updateData = $ShippingCompany->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.shipping-company.index')
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
    public function destroy(ShippingCompany $ShippingCompany)
    {
        $ShippingCompany->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.shipping-company.index')
            ]
        );
    }
}
