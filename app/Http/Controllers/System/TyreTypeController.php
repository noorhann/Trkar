<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

use App\Helpers\Helper;

use App\Models\TyreType;

class TyreTypeController extends SystemController
{
    public function index(Request $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  TyreType::select([
                'id',
                'name_en',
                'name_ar',
                'image',
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
                    $query->where('name_ar', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    
                    __('Name (AR)'),
                    __('Name (EN)'),
                    __('Image')

                ])
                
                ->addColumn('name_ar')
                ->addColumn('name_en')
                ->addColumn('image', function ($data) {
                    if ($data->image == null) {
                        return '--';
                    }

                    return '<img src="' . Helper::path() . '/' .  $data->image . '" style="width: 40px; height: 40px;" />';
                })
              
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Tyre Types')
            ];

            $this->viewData['pageTitle'] = __('Tyre Types');

            return $this->view('tyre_type.index', $this->viewData);
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
    public function destroy(TyreType $tyreType)
    {
        abort(404);
    }
}
