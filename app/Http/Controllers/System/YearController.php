<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use App;
use App\Helpers\Helper;
use App\Http\Requests\YearFormRequest;
use App\Models\Category;
use App\Support\Collection;
use Illuminate\Validation\Rule;

class YearController extends SystemController
{
    public function index(YearFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Year::select([
                'id',
                'year',
                'status',
                'created_at'
            ]);
            if ($request->created_at1 && $request->created_at2) {
                $eloquentData->whereBetween('created_at', [
                    $request->created_at1 . ' 00:00:00',
                    $request->created_at2 . ' 23:59:59'
                ]);
            }


            if ($request->name) {
                $eloquentData->where(function ($query) use ($request) {
                    $query->where('year',  $request->name);
                });
            }
            if ($request->status == 2) {
                $status = 0;
                $eloquentData->where('status', $status);
            } elseif ($request->status == 1) {
                $eloquentData->where('status', $request->status);
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([

                    __('Year'),
                    __('Status'),
                    __('Action')

                ])


                ->addColumn('year')

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.year.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.year.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.year.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.year.edit')) {
                        $edit = '<a href="' . route('admin.year.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.year.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.year.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Years')
            ];

            $this->viewData['pageTitle'] = __('Years');

            return $this->view('year.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Year'),
            'url' => route('admin.year.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Year'),
        ];

        $this->viewData['pageTitle'] = __('Create Year');

        return $this->view('year.create', $this->viewData);
    }

    public function store(YearFormRequest $request)
    {
        $requestData = $request->all();
        $insertData = Year::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.year.create')
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
    public function edit(Year $year)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Year'),
            'url' => route('admin.year.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $year->year]),
        ];

        $this->viewData['pageTitle'] = __('Edit Year');
        $this->viewData['result'] = $year;

        return $this->view('year.create', $this->viewData);
    }


    public function update(YearFormRequest $request, Year $year)
    {

        $requestData = $request->all();
        $updateData = $year->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.year.index')
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
    public function destroy(Year $year)
    {
        $year->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.year.index')
            ]
        );
    }
    public function approvedStatus(Year $year)
    {
        if ($year->status == 0) {
            $approved = 1;
        } elseif ($year->status == 1) {
            $approved = 0;
        }
        $updateData = $year->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.year.index')
            ]
        );
    }
}
