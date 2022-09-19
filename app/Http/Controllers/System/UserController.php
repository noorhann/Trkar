<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App;
use App\Helpers\Helper;
use App\Http\Requests\UserFormRequest;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Support\Collection;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends SystemController
{
    public function index(UserFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  User::select([
                'id',
                'uuid',
                'username',
                'email',
                'phone',
                'country_id',
                'city_id',
                'area_id',
                'in_block',
                'image',
                'created_at'
            ]);
            if ($request->created_at1 && $request->created_at2) {
                $eloquentData->whereBetween('created_at', [
                    $request->created_at1 . ' 00:00:00',
                    $request->created_at2 . ' 23:59:59'
                ]);
            }

            if ($request->status == 1) {
                $status = null;
                $eloquentData->where('in_block', $status);
            } elseif ($request->status == 2) {
                $eloquentData->where('in_block', '!=', null);
            }
            if ($request->name) {
                $eloquentData->where(function ($query) use ($request) {
                    $query->where('uuid', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('username', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('email', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('phone', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('country', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        })
                        ->orWhereHas('area', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        })
                        ->orWhereHas('city', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([

                    __('uuid'),
                    __('User Name'),
                    __('Email'),
                    __('Phone'),
                    __('Country'),
                    __('City'),
                    __('Area'),
                    __('Image'),
                    __('Block'),
                    __('Action')

                ])

                ->addColumn('uuid')
                ->addColumn('username')
                ->addColumn('email')
                ->addColumn('phone')
                ->addColumn('country', function ($data) {
                    if (isset($data->country)) {
                        return $data->country->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('city', function ($data) {
                    if (isset($data->city)) {
                        return $data->city->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('area', function ($data) {
                    if (isset($data->area)) {
                        return $data->area->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })

                ->addColumn('image', function ($data) {
                    if ($data->image == null) {
                        return '--';
                    }

                    return '<img src="' . Helper::path() . '/' .  $data->image . '" style="width: 40px; height: 40px;" />';
                })

                ->addColumn('block', function ($data) {
                    if ($data->in_block == null) {
                        return '<span class="badge badge-soft-success">' . __(' Active') . '</span>';
                    } else {
                        return '<span class="badge badge-soft-danger">' . __(' In-Active') . '</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    // $delete = '';
                    $show = '';
                    if (Helper::adminCan('admin.user.edit')) {
                        $edit = '<a href="' . route('admin.user.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.user.show')) {
                        $show = ' <a href="' . route('admin.user.show', $data->id) . '" class="action-icon"> <i class="mdi mdi-eye-circle-outline"></i></a>';
                    }
                    // if (Helper::adminCan('admin.user.destroy')) {
                    //     $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.user.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    // }
                    return $edit . $show;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Customer')
            ];

            $this->viewData['pageTitle'] = __('Customer');

            return $this->view('user.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Customer'),
            'url' => route('admin.user.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Add Customer'),
        ];

        $this->viewData['pageTitle'] = __('Add Customer');

        return $this->view('user.create', $this->viewData);
    }

    public function store(UserFormRequest $request)
    {
        $requestData = $request->all();
        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("user/)",  $request->file('image'));
        }
        if ($requestData['in_block'] == 1) {
            $requestData['in_block'] = null;
        } else {
            $requestData['in_block'] = Carbon::now();
        }
        $requestData['uuid'] =  Helper::IDGenerator('users', 'uuid', 4, 'C');
        $requestData['password'] =  bcrypt($requestData['password']);

        $insertData = User::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.user.create')
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
    public function edit(User $user)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Customer'),
            'url' => route('admin.user.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $user->{'name_' . App::getLocale()}]),
        ];
        $this->viewData['cities']  = City::where('country_id',$user->country_id)->get();
        $this->viewData['areas']  = Area::where('city_id',$user->city_id)->get();

        $this->viewData['pageTitle'] = __('Edit Customer');
        $this->viewData['result'] = $user;
        $this->viewData['userAddresses'] = $user->userAddresses->where('default',1)->first();

        return $this->view('user.create', $this->viewData);
    }


    public function update(UserFormRequest $request, User $user)
    {
        $requestData = $request->all();

        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("user/)",  $request->file('image'));
        } else {
            $requestData['image'] =  $user->image;
        }
        if ($requestData['in_block'] == 1) {
            $requestData['in_block'] = null;
        } else {
            $requestData['in_block'] = Carbon::now();
        }

        $updateData = $user->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.user.index')
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

    public function show(User $user)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Customer'),
            'url' => route('admin.user.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Show (:name)', ['name' => $user->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Show Customer');
        $this->viewData['result'] = $user;
        
        return $this->view('user.show', $this->viewData);
    }
    public function destroy(User $user)
    {
        $user->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.user.index')
            ]
        );
    }
    public function getCity($country)
    {
        // dd($country);
        $cities = City::where('country_id', $country)->get();
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $cities
        );
    }
    public function getArea($areaId)
    {
        $areas = Area::where('city_id', $areaId)->get();
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $areas

        );
    }
}
