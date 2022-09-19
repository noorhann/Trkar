<?php

namespace App\Http\Controllers\System;

use App\Http\Requests\PermissionGroupFormRequest;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Auth;
use App;
use App\Helpers\Helper;
use Illuminate\Support\Facades\File;

class PermissionGroupController extends SystemController
{

    public function index(Request $request){

        if($request->isTablePagination){

            $eloquentData = PermissionGroup::select([
                'id',
                'name',
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
                    $query->where('name', 'LIKE', '%' . $request->name . '%');
                });
            }
            $eloquentData->orderBy('id', 'DESC');

            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    
                    __('Name'),
                    __('Created At'),
                    __('Action')

                ])
                
                ->addColumn('name')
              
                ->addColumn('created_at',function($data){
                 return  $data->created_at;
                })

             
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (Helper::adminCan('admin.permission-group.edit')) {
                        $edit = '<a href="' . route('admin.permission-group.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }
                    if (Helper::adminCan('admin.permission-group.destroy')) {
                        $delete = '<a href="javascript:void(0);" onclick="deleteRecord(\'' . route('admin.permission-group.destroy', $data->id) . '\');" data-token="' . csrf_token() . '" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
                    }
                    return $edit . $delete;
              
                })
                ->render($request->items_per_page);
        }else{
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Permission Groups')
            ];

            $this->viewData['pageTitle'] = __('Permission Groups');

            return $this->view('permission-group.index',$this->viewData);
        }
    }

    public function create(){
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Permission Groups'),
            'url'=> route('admin.permission-group.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Permission Group'),
        ];

        $this->viewData['pageTitle'] = __('Create Permission Group');

        $this->viewData['permissions'] = $this->permissions();

        return $this->view('permission-group.create',$this->viewData);
    }

    public function store(PermissionGroupFormRequest $request){

        $requestData = $request->all();
// dd($requestData);
        $requestData['permissions'] = [];
        foreach (collect($this->permissions())->pluck('permissions')->toArray() as $value){
            foreach ($value as $key => $onePermission){
                if(in_array($key,$request->permission_ids)){
                    $requestData['permissions'][] = $onePermission;
                }
            }
        }

        $requestData['permissions'] = \Arr::flatten($requestData['permissions']);

        $insertData = PermissionGroup::create($requestData);

        if($insertData){
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url'=> route('admin.permission-group.index')
                ]
            );
        }else{
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to add data')
            );
        }

    }

    public function edit(PermissionGroup $permission_group){

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Permission Groups'),
            'url'=> route('admin.permission-group.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $permission_group->name
        ];

        $this->viewData['pageTitle'] = __('Edit Permission Group');
        $this->viewData['result'] = $permission_group;

        $this->viewData['permissions'] = $this->permissions();

        return $this->view('permission-group.create',$this->viewData);

    }

    public function update(PermissionGroupFormRequest $request, PermissionGroup $permission_group)
    {
        $requestData = $request->all();

        $requestData['permissions'] = [];
        foreach (collect($this->permissions())->pluck('permissions')->toArray() as $value){
            foreach ($value as $key => $onePermission){
                if(in_array($key,$request->permission_ids)){
                    $requestData['permissions'][] = $onePermission;
                }
            }
        }

        $requestData['permissions'] = \Arr::flatten($requestData['permissions']);

        $updateData = $permission_group->update($requestData);
        if($updateData){
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url'=> route('admin.permission-group.index')
                ]
            );
        }else{
            return $this->response(
                false,
                11001,
                __('Sorry, the system is unable to modify the data')
            );
        }

    }

    public function show(Request $request){
        abort(404);
    }

    public function destroy(Request $request){
        abort(404);
    }


    private function permissions(){
        return File::getRequire(app_path('Enum/Permissions.php'));
    }

}
