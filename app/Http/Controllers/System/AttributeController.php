<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App;
use App\Helpers\Helper;
use App\Http\Requests\OriginalCountryFormRequest;
use App\Models\Attribute;


class AttributeController extends SystemController
{
    public function index(Request $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Attribute::select([
                'id',
                'name',
                'status',
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
                    $query->where('name', 'LIKE', '%' . $request->name . '%');
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Name'),
                    __('Status'),
                    __('Action')

                ])
                ->addColumn('name')
                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.attribute.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.attribute.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.attribute.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('action', function ($data) {

                    if (Helper::adminCan('admin.attribute.destroy')) {
                        return '
                     <a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.attribute.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                            ';
                        return '';
                    }
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Attributes')
            ];

            $this->viewData['pageTitle'] = __('Attributes');

            return $this->view('attribute.index', $this->viewData);
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
    public function edit()
    {

        abort(404);
    }


    public function update(Request $request)
    {
        abort(404);
    }

    public function show()
    {
        abort(404);
    }
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.attribute.index')
            ]
        );
    }
    public function approvedStatus(Attribute $attribute)
    {
        if ($attribute->status == 0) {
            $approved = 1;
        } elseif ($attribute->status == 1) {
            $approved = 0;
        }
        $updateData = $attribute->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.attribute.index')
            ]
        );
    }
}
