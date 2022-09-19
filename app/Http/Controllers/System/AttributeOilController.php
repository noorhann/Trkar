<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App;
use App\Helpers\Helper;
use App\Http\Controllers\System\SystemController;
use App\Http\Requests\AttributeOilFormRequest;
use App\Models\AttributeOil;
use App\Models\TyreType;

class AttributeOilController extends SystemController
{
    public function index(AttributeOilFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  AttributeOil::select([
                'id',
                'parent_id',
                'value',
                'attribute_id',
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
                    $query->where('value', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('attribute', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Attribute'),
                    __('Value'),
                    __('SAE viscosity grade'),
                    __('Manufacturer'),
                    __('OEM Approval'),
                    __('Status'),
                    __('Action')

                ])



                ->addColumn('attribute', function ($data) {
                    if (isset($data->attribute)) {
                        return $data->attribute->name;
                    } else {
                        return '--';
                    }
                })


                ->addColumn('value')

                
                ->addColumn('Sae', function ($data) {
                    $value = __('--');
                    $parents = AttributeOil::find($data->id)->parents->reverse();
                    foreach ($parents as $parent) {
                        if ($parent->attribute_id == 8) {
                            $value = $parent->value;
                        }
                    }
                    return $value;
                })
                ->addColumn('manufacturer', function ($data) {
                    $value = __('--');
                    $parents = AttributeOil::find($data->id)->parents->reverse();
                    foreach ($parents as $parent) {
                        if ($parent->attribute_id == 7) {
                            $value = $parent->value;
                        }
                    }
                    return $value;
                })
                ->addColumn('oem', function ($data) {
                    $value = __('--');
                    $parents = AttributeOil::find($data->id)->parents->reverse();
                    foreach ($parents as $parent) {
                        if ($parent->attribute_id == 9) {
                            $value = $parent->value;
                        }
                    }
                    return $value;
                })
           
                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.attribute-oil.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.attribute-oil.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.attribute-oil.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('action', function ($data) {

                    return '
                     <a href="' . route('admin.attribute-oil.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Attribute Oil')
            ];

            $this->viewData['pageTitle'] = __('Attribute Oil');
            $this->viewData['tyreTypes'] = TyreType::get();

            return $this->view('attribute_oil.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Attribute Oil'),
            'url' => route('admin.attribute-oil.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Attribute Oil'),
        ];

        $this->viewData['pageTitle'] = __('Create Attribute Oil');

        return $this->view('attribute_oil.create', $this->viewData);
    }

    public function store(AttributeOilFormRequest $request)
    {

        $requestData = $request->all();

        if ($request->attribute_id == 7) {
            if ($request->sae != "null") {
                $requestData['parent_id'] = $requestData['sae'];
            }
        }

        if ($request->attribute_id == 9) {
            if ($request->sae != "null") {
                $requestData['parent_id'] = $requestData['sae'];
            }
            if ($request->manufacturer != "null") {
                $requestData['parent_id'] = $requestData['manufacturer'];
            }
        }
        if ($request->attribute_id == 10) {
            if ($request->sae != "null") {

                $requestData['parent_id'] = $requestData['sae'];
            }
            if ($request->manufacturer != "null") {
                $requestData['parent_id'] = $requestData['manufacturer'];
            }
            if ($request->oem != "null") {
                $requestData['parent_id'] = $requestData['oem'];
            }
        }


        // if(){}
        $insertData = AttributeOil::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.attribute-oil.create')
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
    public function edit(AttributeOil $AttributeOil)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Attribute Oil'),
            'url' => route('admin.attribute-oil.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $AttributeOil->value]),
        ];
        $parent = $AttributeOil->parents->reverse();

        $this->viewData['pageTitle'] = __('Edit Attribute Oil');
        $this->viewData['result'] = $AttributeOil;
        $this->viewData['parent'] = $parent;
        // dd($parent);

        return $this->view('attribute_oil.create', $this->viewData);
    }


    public function update(AttributeOilFormRequest $request, AttributeOil $AttributeOil)
    {
        $requestData = $request->all();
        if ($request->attribute_id == 7) {
            if ($request->sae != "null") {
                $requestData['parent_id'] = $requestData['sae'];
            }
        }

        if ($request->attribute_id == 9) {
            if ($request->sae != "null") {
                $requestData['parent_id'] = $requestData['sae'];
            }
            if ($request->manufacturer != "null") {
                $requestData['parent_id'] = $requestData['manufacturer'];
            }
        }
        if ($request->attribute_id == 10) {
            if ($request->sae != "null") {

                $requestData['parent_id'] = $requestData['sae'];
            }
            if ($request->manufacturer != "null") {
                $requestData['parent_id'] = $requestData['manufacturer'];
            }
            if ($request->oem != "null") {
                $requestData['parent_id'] = $requestData['oem'];
            }
        }
        $updateData = $AttributeOil->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.attribute-oil.index')
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

    // public function show(Request $request, $AttributeOil)
    // {
    //     if ($request->isTablePagination) {
    //         $eloquentData =  AttributeOil::select([
    //             'id',
    //             'season_id',
    //             'parent_id',
    //             'value',
    //             'attribute_id',
    //             'type_id',
    //             'created_at'
    //         ]);
    //         if ($request->created_at1 && $request->created_at2) {
    //             $eloquentData->whereBetween('created_at', [
    //                 $request->created_at1 . ' 00:00:00',
    //                 $request->created_at2 . ' 23:59:59'
    //             ]);
    //         }

    //         if ($request->name) {
    //             $eloquentData->where(function ($query) use ($request) {
    //                 $query
    //                     ->where('value', 'LIKE', '%' . $request->name . '%')
    //                     ->orWhereHas('type', function ($q) use ($request) {
    //                         $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
    //                             ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
    //                     })->orWhereHas('attribute', function ($q) use ($request) {
    //                         $q->where('name', 'LIKE', '%' . $request->name . '%');
    //                     })->orWhereHas('season', function ($q) use ($request) {
    //                         $q->where('name', 'LIKE', '%' . $request->name . '%');
    //                     });
    //             });
    //         }
    //         $eloquentData->orderBy('id', 'DESC');
    //         return Helper::tablePagination()
    //             ->eloquent($eloquentData)
    //             ->setHeadColumns([
    //                 __('Type'),
    //                 __('Season'),
    //                 __('Width'),
    //                 __('Hight'),
    //                 __('Diameter'),
    //                 __('Attribute'),
    //                 __('Value'),
    //                 __('Action')

    //             ])

    //             ->addColumn('type', function ($data) {
    //                 if (isset($data->type)) {
    //                     return $data->type->name_ar;
    //                 } else {
    //                     return '--';
    //                 }
    //             })
    //             ->addColumn('season', function ($data) {
    //                 if (isset($data->season)) {
    //                     return $data->season->name;
    //                 } else {
    //                     return '--';
    //                 }
    //             })
    //             ->addColumn('Width', function ($data) {
    //                 $value = __('--');
    //                 $parents = AttributeOil::find($data->id)->parents->reverse();
    //                 foreach ($parents as $parent) {
    //                     if ($parent->attribute_id == 1) {
    //                         $value = $parent->value;
    //                     }
    //                 }
    //                 return $value;
    //             })
    //             ->addColumn('hight', function ($data) {
    //                 $value = __('--');
    //                 $parents = AttributeOil::find($data->id)->parents->reverse();
    //                 foreach ($parents as $parent) {
    //                     if ($parent->attribute_id == 2) {
    //                         $value = $parent->value;
    //                     }
    //                 }
    //                 return $value;
    //             })
    //             ->addColumn('hight', function ($data) {
    //                 $value = __('--');
    //                 $parents = AttributeOil::find($data->id)->parents->reverse();
    //                 foreach ($parents as $parent) {
    //                     if ($parent->attribute_id == 2) {
    //                         $value = $parent->value;
    //                     }
    //                 }
    //                 return $value;
    //             })
    //             ->addColumn('diameter', function ($data) {
    //                 $value = __('--');
    //                 $parents = AttributeOil::find($data->id)->parents->reverse();
    //                 foreach ($parents as $parent) {
    //                     if ($parent->attribute_id == 3) {
    //                         $value = $parent->value;
    //                     }
    //                 }
    //                 return $value;
    //             })

    //             ->addColumn('attribute', function ($data) {
    //                 if (isset($data->attribute)) {
    //                     return $data->attribute->name;
    //                 } else {
    //                     return '--';
    //                 }
    //             })


    //             ->addColumn('value')




    //             ->addColumn('action', function ($data) {
    //                 $edit = '';

    //                 if (Helper::adminCan('admin.attribute-oil.edit')) {
    //                     $edit = '<a href="' . route('admin.attribute-oil.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
    //                 }

    //                 return $edit;
    //             })
    //             ->render($request->items_per_page);
    //     } else {
    //         // View Data
    //         $this->viewData['breadcrumb'][] = [
    //             'text' => __(' Attribute Oil')
    //         ];

    //         $this->viewData['pageTitle'] = __('Attribute Oil');
    //         $this->viewData['tyreTypes'] = TyreType::get();
    //     }

    //     return $this->view('attribute_oil.show', $this->viewData);
    // }
    public function destroy(AttributeOil $AttributeOil)
    {
        $AttributeOil->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.attribute-oil.show', 1)
            ]
        );
    }

    public function searchAttributeSae(Request $request)
    {
        if ($request->page) {
            if ($request->parent_id) {
                $attributeOils = AttributeOil::where('attribute_id', 8)
                    ->where('parent_id', $request->parent_id)
                    ->get();
            } else {
                $attributeOils = AttributeOil::where('attribute_id', 8)
                    ->get();
            }
        } else {
            if ($request->parent_id != null) {
                $attributeOils = AttributeOil::where('parent_id', $request->parent_id)
                    ->where('attribute_id', 8)
                    ->get();
            } else {
                $attributeOils = AttributeOil::where('type_id', $request->type_id)
                    ->where('attribute_id', 8)
                    ->get();
            }
        }
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeOils
        );
    }
    public function searchAttributeManufacturer(Request $request)
    {

        if ($request->page) {
            if ($request->parent_id) {
                $attributeOils = AttributeOil::where('attribute_id', 7)
                    ->where('parent_id', $request->parent_id)
                    ->get();
            } else {
                $attributeOils = AttributeOil::where('attribute_id', 7)
                    ->get();
            }
        } else {
            $attributeOils = AttributeOil::where('type_id', $request->type_id)
                ->where('attribute_id', 7)
                ->get();
        }
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeOils
        );
    }

    public function searchAttributeOem(Request $request)
    {
        if ($request->page) {
            if ($request->parent_id) {
                $attributeOils = AttributeOil::where('attribute_id', 9)
                    ->where('parent_id', $request->parent_id)
                    ->get();
            } else {
                $attributeOils = AttributeOil::where('attribute_id', 9)
                    ->get();
            }
        } else {

            if ($request->parent_id != null) {
                $attributeOils = AttributeOil::where('parent_id', $request->parent_id)
                    ->where('attribute_id', 9)
                    ->get();
            } else {
                $attributeOils = AttributeOil::where('attribute_id', 9)
                    ->get();
            }
        }
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeOils
        );
    }
    public function searchAttributeSpecification(Request $request)
    {
        if ($request->page) {
            if ($request->parent_id) {
                $attributeOils = AttributeOil::where('attribute_id', 10)
                    ->where('parent_id', $request->parent_id)
                    ->get();
            } else {
                $attributeOils = AttributeOil::where('attribute_id', 10)
                    ->get();
            }
        } else {
            $attributeOils = AttributeOil::where('attribute_id', 10)
                ->get();
        }
        if ($request->parent_id == null) {
            $attributeOils = AttributeOil::where('attribute_id', 10)
                ->get();
        } else {
            $attributeOils = AttributeOil::where('parent_id', $request->parent_id)
                ->where('attribute_id', 10)
                ->get();
        }
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeOils
        );
    }
    public function approvedStatus(AttributeOil $attributeOil)
    {
        if ($attributeOil->status == 0) {
            $approved = 1;
        } elseif ($attributeOil->status == 1) {
            $approved = 0;
        }
        $updateData = $attributeOil->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.attribute-oil.index')
            ]
        );
    }
}
