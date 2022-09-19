<?php

namespace App\Http\Controllers\System;

use App\Models\ActivityLog;
use App;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends SystemController
{
    public function index(Request $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  ActivityLog::select([
                'id',
                'description',
                'subject_type',
                'subject_id',
                'causer_type',
                'properties',
                'causer_id',
                'created_at',
            ]);
            if ($request->created_at1 && $request->created_at2) {
                $eloquentData->whereBetween('created_at', [
                    $request->created_at1 . ' 00:00:00',
                    $request->created_at2 . ' 23:59:59'
                ]);
            }

            if ($request->subject_id) {
                $eloquentData->where('subject_id', 'LIKE', '%' . $request->subject_id . '%');
            }
            if ($request->subject_type) {
                $eloquentData->where('subject_type', 'LIKE', '%' . $request->subject_type . '%');
            }
            if ($request->name) {
                $eloquentData->where(function ($query) use ($request) {
                    $query->where('description', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('subject_type', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('subject_id', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('causer_type', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('causer_id', 'LIKE', '%' . $request->name . '%');
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Subject Type'),
                    __('Subject Id'),
                    __('Responsible Person'),
                    __('Permission Type'),
                    __('Admin ID'),
                    __('Action'),
                    __('Created At'),
                    __('Actions'),
                ])
                ->addColumn('subject_type', function ($data) {
                    $expload = explode("\\", $data->subject_type);
                    $count = preg_match_all("/[A-Z]/", $expload[2]);
                    if ($count > 1) {
                        $text = preg_split('/(?=[A-Z])/', $expload[2]);
                        unset($text[0]);
                        $implode = implode(" ", $text);
                        return $implode . ' (' . __($implode) . ')';
                    } else {
                        return $expload[2] . ' (' . __($expload[2]) . ')';
                    }
                })
                ->addColumn('subject_id')
                ->addColumn('user', function ($data) {
                    if ($data->causer_type == 'Vendor') {
                        if (isset($data->vendor)) {
                            return $data->vendor->username;
                        } else {
                            return '--';
                        }
                    } elseif ($data->causer_type == 'Admin') {
                        if (isset($data->admin)) {
                            return $data->admin->username;
                        } else {
                            return '--';
                        }
                    } elseif ($data->causer_type == 'User') {
                        if (isset($data->user)) {
                            return $data->user->username;
                        } else {
                            return '--';
                        }
                    }
                })

                ->addColumn('causer_type')
                ->addColumn('causer_id')
                ->addColumn('description')
                ->addColumn('created_at')
                ->addColumn('action', function ($data) {

                    $show = '';

                    if (Helper::adminCan('admin.activity-log.show')) {
                        $show = ' <a href="' . route('admin.activity-log.show', $data->id) . '" class="action-icon"> <i class="mdi mdi-eye-circle-outline"></i></a>';
                    }

                    return $show;
                })




                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('Activity Log')
            ];

            $this->viewData['pageTitle'] = __('Activity Log');

            return $this->view('activity_log.index', $this->viewData);
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
    public function edit(ActivityLog $ActivityLog)
    {

        abort(404);
    }


    public function update(Request $request, ActivityLog $ActivityLog)
    {
        abort(404);
    }

    public function show(ActivityLog $ActivityLog)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Activity Log'),
            'url' => route('admin.activity-log.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Show (:name)', ['name' => $ActivityLog->description]),
        ];

        $this->viewData['pageTitle'] = __('Show Activity Log');
        $activitiesdata = Activity::where('id', $ActivityLog->id)->first()->toArray();
        $this->viewData['result'] = $activitiesdata['properties'];
        $this->viewData['description'] = $ActivityLog->description;
        $this->viewData['ActivityLog'] = $ActivityLog->subject_type::find($ActivityLog->subject_id);

        return $this->view('activity_log.show', $this->viewData);
    }
    public function destroy(ActivityLog $ActivityLog)
    {
        abort(404);
    }
}
