<?php

namespace App\Http\Controllers\System;

use App\Models\StoreAuditLog;

use Illuminate\Support\Facades\Storage;

use App;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class StoreAuditLogController extends SystemController
{
    public function index(Request $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  StoreAuditLog::select([
                'id',
                'user_id',
                'discription',
                'model_type',
                'store_id ',
                'properties',
                'model_id',
            ]);
            if ($request->created_at1 && $request->created_at2) {
                $eloquentData->whereBetween('created_at', [
                    $request->created_at1 . ' 00:00:00',
                    $request->created_at2 . ' 23:59:59'
                ]);
            }

            if ($request->name) {
                $eloquentData->where(function ($query) use ($request) {
                    $query->orWhereHas('user', function ($q) use ($request) {
                        $q->where('username', 'LIKE', '%' . $request->name . '%');
                    })
                    ->orWhereHas('store', function ($q) use ($request) {
                        $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                            ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                    })
                        ->orWhereHas('model', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    
                    __('Discription'),
                    __('Model Type'),
                    __('Properties'),
                    __('User'),
                    __('Store'),
                    __('Model'),

                ])
                
                ->addColumn('discription')
                ->addColumn('model_type')
                ->addColumn('properties')
                ->addColumn('user', function ($data) {
                    if (isset($data->user)) {
                        return $data->user->username;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('model', function ($data) {
                    if (isset($data->model)) {
                        return $data->model->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('store', function ($data) {
                    if (isset($data->store)) {
                        return $data->store->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })


  
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Store Audit Log')
            ];

            $this->viewData['pageTitle'] = __('Store Audit Log');

            return $this->view('store_audit_log.index', $this->viewData);
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
    public function edit(StoreAuditLog $StoreAuditLog)
    {

        abort(404);
    }


    public function update(Request $request, StoreAuditLog $StoreAuditLog)
    {
        abort(404);
    }

    public function show(StoreAuditLog $StoreAuditLog)
    {
      abort(404);
    }
    public function destroy(StoreAuditLog $StoreAuditLog)
    {
        abort(404);
    }
}
