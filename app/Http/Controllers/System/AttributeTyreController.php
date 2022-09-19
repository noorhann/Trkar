<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App;
use App\Helpers\Helper;
use App\Http\Controllers\System\SystemController;
use App\Http\Requests\AttributeTyreFormRequest;
use App\Models\AttributeTyre;
use App\Models\Season;
use App\Models\TyreType;

class AttributeTyreController extends SystemController
{
    public function index(AttributeTyreFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  AttributeTyre::select([
                'id',
                'season_id',
                'parent_id',
                'value',
                'attribute_id',
                'type_id',
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
                    $query
                        ->where('value', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('type', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        })->orWhereHas('attribute', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->name . '%');
                        })->orWhereHas('season', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Type'),
                    __('Season'),
                    __('Width'),
                    __('Attribute'),
                    __('Value'),
                    __('Parent Value'),
                    __('Status'),
                    __('Action')

                ])

                ->addColumn('type', function ($data) {
                    if (isset($data->type)) {
                        return $data->type->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('season', function ($data) {
                    if (isset($data->season)) {
                        return $data->season->name;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('parent_id', function ($data) {
                    $arrays = [];
                    if ($data->parent_id == 0) {
                        return __('Width');
                    } else {

                        $parents = AttributeTyre::find($data->id)->parents->reverse();
                        foreach ($parents as $parent) {
                            if ($parent->parent_id == 0) {
                                $width = $parent->value;
                            }
                        }
                        return $width;
                    }
                })
                ->addColumn('attribute', function ($data) {
                    if (isset($data->attribute)) {
                        return $data->attribute->name;
                    } else {
                        return '--';
                    }
                })


                ->addColumn('value')

                ->addColumn('parent', function ($data) {
                    if (isset($data->parent)) {
                        return $data->parent->value;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.attribute-tyre.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.attribute-tyre.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.attribute-tyre.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })

                ->addColumn('action', function ($data) {

                    return '
                     <a href="' . route('admin.attribute-tyre.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Attribute Tyres')
            ];

            $this->viewData['pageTitle'] = __('Attribute Tyres');
            $this->viewData['tyreTypes'] = TyreType::get();

            return $this->view('attribute_tyre.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Attribute Tyre'),
            'url' => route('admin.attribute-tyre.show', 1)
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Attribute Tyre'),
        ];
        $this->viewData['seasons'] = \App\Models\Season::where('id', '!=', '4')->get(['id', 'name']);
        $this->viewData['pageTitle'] = __('Create Attribute Tyre');

        return $this->view('attribute_tyre.create', $this->viewData);
    }

    public function store(AttributeTyreFormRequest $request)
    {
        $requestData = $request->all();
        if ($requestData['attribute_id'] == 1) {
            $requestData['parent_id'] = 0;
        } elseif ($requestData['attribute_id'] == 2) {
            $requestData['parent_id'] = $requestData['width'];
        } elseif ($requestData['attribute_id'] == 3) {
            if ($requestData['hight'] != null) {
                $requestData['parent_id'] = $requestData['hight'];
            } elseif ($requestData['width'] != null) {
                $requestData['parent_id'] = $requestData['width'];
            }
        } elseif ($requestData['attribute_id'] == 4 || $requestData['attribute_id'] == 5 || $requestData['attribute_id'] == 6 || $requestData['attribute_id'] == 7) {

            if (isset($requestData['diameter'])) {
                $requestData['parent_id'] = $requestData['diameter'];
            } 
            if (isset($requestData['hight'])) {
                $requestData['parent_id'] = $requestData['hight'];
            }
            if (isset($requestData['width'])) {
                $requestData['parent_id'] = $requestData['width'];
            }
        
        }
        if ($requestData['season_id'] == null) {
            $requestData['season_id'] = 4;
        }
        $insertData = AttributeTyre::create($requestData);

        if ($insertData) {
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.attribute-tyre.create')
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
    public function edit(AttributeTyre $AttributeTyre)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Attribute Tyre'),
            'url' => route('admin.attribute-tyre.show', 1)
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $AttributeTyre->value]),
        ];
        // $arrays = [];
        // $parents = AttributeTyre::find($AttributeTyre->id)->parents->reverse();
        // foreach ($parents as $parent) {
        // if($parent->attribute_id == 1){
        //     $this->viewData['width'] = $parent->value;
        // }elseif($parent->attribute_id == 2){
        //     $this->viewData['hight'] = $parent->value;
        // }elseif($parent->attribute_id == 3){
        //     $this->viewData['hight'] = $parent->value;
        // }elseif($parent->attribute_id == 4){
        //     $this->viewData['hight'] = $parent->value;
        // }elseif($parent->attribute_id == 5){
        //     $this->viewData['hight'] = $parent->value;
        // }elseif($parent->attribute_id == 6){
        //     $this->viewData['hight'] = $parent->value;
        // }elseif($parent->attribute_id == 7){
        //     $this->viewData['hight'] = $parent->value;
        // }
        // $arrays[] = $parent->value;
        // }
        // dd($arrays);
        $this->viewData['pageTitle'] = __('Edit Attribute Tyre');
        $this->viewData['result'] = $AttributeTyre;
        $this->viewData['seasons'] = \App\Models\Season::where('id', '!=', '4')->get(['id', 'name']);

        return $this->view('attribute_tyre.create', $this->viewData);
    }


    public function update(AttributeTyreFormRequest $request, AttributeTyre $AttributeTyre)
    {
        $requestData = $request->all();
        if ($requestData['attribute_id'] == 1) {
            $requestData['parent_id'] = 0;
        } elseif ($requestData['attribute_id'] == 2) {
            $requestData['parent_id'] = $requestData['width'];
        } elseif ($requestData['attribute_id'] == 3) {
            if ($requestData['hight'] != null) {
                $requestData['parent_id'] = $requestData['hight'];
            } elseif ($requestData['width'] != null) {
                $requestData['parent_id'] = $requestData['width'];
            }
        } elseif ($requestData['attribute_id'] == 4 || $requestData['attribute_id'] == 5 || $requestData['attribute_id'] == 6 || $requestData['attribute_id'] == 7) {
            if ($requestData['diameter'] != null) {
                $requestData['parent_id'] = $requestData['diameter'];
            } elseif ($requestData['hight'] != null) {
                $requestData['parent_id'] = $requestData['hight'];
            } elseif ($requestData['width'] != null) {
                $requestData['parent_id'] = $requestData['width'];
            }
        }
        if ($requestData['season_id'] == null) {
            $requestData['season_id'] = 4;
        }
        $updateData = $AttributeTyre->update($requestData);
        if ($updateData) {
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.attribute-tyre.show', 1)
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

    public function show(Request $request, $AttributeTyre)
    {
        if ($request->isTablePagination) {
            $eloquentData =  AttributeTyre::select([
                'id',
                'season_id',
                'parent_id',
                'value',
                'attribute_id',
                'type_id',
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
                    $query
                        ->where('value', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('type', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        })->orWhereHas('attribute', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->name . '%');
                        })->orWhereHas('season', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Type'),
                    __('Season'),
                    __('Width'),
                    __('Hight'),
                    __('Diameter'),
                    __('Attribute'),
                    __('Value'),
                    __('Status'),
                    __('Action')

                ])

                ->addColumn('type', function ($data) {
                    if (isset($data->type)) {
                        return $data->type->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('season', function ($data) {
                    if (isset($data->season)) {
                        return $data->season->name;
                    } else {
                        return '--';
                    }
                })
                ->addColumn('Width', function ($data) {
                    $value = __('--');
                    $parents = AttributeTyre::find($data->id)->parents->reverse();
                    foreach ($parents as $parent) {
                        if ($parent->attribute_id == 1) {
                            $value = $parent->value;
                        }
                    }
                    return $value;
                })
                ->addColumn('hight', function ($data) {
                    $value = __('--');
                    $parents = AttributeTyre::find($data->id)->parents->reverse();
                    foreach ($parents as $parent) {
                        if ($parent->attribute_id == 2) {
                            $value = $parent->value;
                        }
                    }
                    return $value;
                })

                ->addColumn('diameter', function ($data) {
                    $value = __('--');
                    $parents = AttributeTyre::find($data->id)->parents->reverse();
                    foreach ($parents as $parent) {
                        if ($parent->attribute_id == 3) {
                            $value = $parent->value;
                        }
                    }
                    return $value;
                })

                ->addColumn('attribute', function ($data) {
                    if (isset($data->attribute)) {
                        return $data->attribute->name;
                    } else {
                        return '--';
                    }
                })


                ->addColumn('value')

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.attribute-tyre.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.attribute-tyre.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.attribute-tyre.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })


                ->addColumn('action', function ($data) {
                    $edit = '';

                    if (Helper::adminCan('admin.attribute-tyre.edit')) {
                        $edit = '<a href="' . route('admin.attribute-tyre.edit', $data->id) . '" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>';
                    }

                    return $edit;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __(' Attribute Tyres')
            ];

            $this->viewData['pageTitle'] = __('Attribute Tyres');
            $this->viewData['tyreTypes'] = TyreType::get();
            $this->viewData['seasons'] = \App\Models\Season::where('id', '!=', '4')->get(['id', 'name']);
        }

        return $this->view('attribute_tyre.show', $this->viewData);
    }
    public function destroy(AttributeTyre $AttributeTyre)
    {
        $AttributeTyre->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.attribute-tyre.show', 1)
            ]
        );
    }

    public function searchAttributeWidth(Request $request)
    {
        if ($request->page) {
            $attributeTyres = AttributeTyre::where('attribute_id', 1)->get();
        } else {
            $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                ->where('type_id', $request->type_id)
                ->where('attribute_id', 1)
                ->get();
        }

        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeTyres
        );
    }
    public function searchAttributeHight(Request $request)
    {
        if ($request->page) {
            $attributeTyres = AttributeTyre::where('attribute_id', 2)
                ->where('parent_id', $request->parent_id)
                ->get();
        } else {
            $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                ->where('type_id', $request->type_id)
                ->where('parent_id', $request->parent_id)
                ->where('attribute_id', 2)
                ->get();
        }
        // dd($attributeTyres);
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeTyres
        );
    }
    public function searchAttributeDiameter(Request $request)
    {
        if ($request->page) {
            $attributeTyres = AttributeTyre::where('attribute_id', 3)
                ->where('parent_id', $request->parent_id)
                ->get();
        } else {
            $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                ->where('type_id', $request->type_id)
                ->where('parent_id', $request->parent_id)
                ->where('attribute_id', 3)
                ->get();
        }
        // dd($attributeTyres);
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeTyres
        );
    }

    public function searchAttributeManufacturer(Request $request)
    {

        if ($request->page) {
            if ($request->parent_id) {
                $attributeTyres = AttributeTyre::where('attribute_id', 7)
                    ->where('parent_id', $request->parent_id)
                    ->get();
            } else {
                $attributeTyres = AttributeTyre::where('attribute_id', 7)
                    ->get();
            }
        } else {
            if ($request->season_id != null) {
                $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                    ->where('type_id', $request->type_id)
                    ->where('attribute_id', 7)
                    ->get();
            }
            if ($request->season_id && $request->parent_id && $request->type_id) {


                $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                    ->where('type_id', $request->type_id)
                    ->where('parent_id', $request->parent_id)
                    ->where('attribute_id', 7)
                    ->get();
            } else {
                $attributeTyres = AttributeTyre::where('type_id', $request->type_id)
                    ->where('attribute_id', 7)
                    ->get();
            }
        }
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeTyres
        );
    }
    public function searchAttributeSpeadRating(Request $request)
    {
        if ($request->page) {
            if ($request->parent_id) {
                $attributeTyres = AttributeTyre::where('attribute_id', 4)
                    ->where('parent_id', $request->parent_id)
                    ->get();
            } else {
                $attributeTyres = AttributeTyre::where('attribute_id', 4)
                    ->get();
            }
        } else {
            if ($request->season_id != null) {
                $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                    ->where('type_id', $request->type_id)
                    ->where('attribute_id', 4)
                    ->get();
            }
            if ($request->season_id != null && $request->parent_id != null) {
                $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                    ->where('type_id', $request->type_id)
                    ->where('parent_id', $request->parent_id)
                    ->where('attribute_id', 4)
                    ->get();
            } else {
                $attributeTyres = AttributeTyre::where('type_id', $request->type_id)
                    ->where('attribute_id', 4)
                    ->get();
            }
        }
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeTyres
        );
    }
    public function searchAttributeAlex(Request $request)
    {
        if ($request->page) {
            if ($request->parent_id) {
                $attributeTyres = AttributeTyre::where('attribute_id', 6)
                    ->where('parent_id', $request->parent_id)
                    ->get();
            } else {
                $attributeTyres = AttributeTyre::where('attribute_id', 6)
                    ->get();
            }
        } else {
            if ($request->season_id != null) {
                $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                    ->where('type_id', $request->type_id)
                    ->where('attribute_id', 6)
                    ->get();
            }
            if ($request->season_id != null && $request->parent_id != null) {
                $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                    ->where('type_id', $request->type_id)
                    ->where('parent_id', $request->parent_id)
                    ->where('attribute_id', 6)
                    ->get();
            } else {
                $attributeTyres = AttributeTyre::where('type_id', $request->type_id)
                    ->where('attribute_id', 6)
                    ->get();
            }
        }
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeTyres
        );
    }
    public function searchAttributeLoad(Request $request)
    {
        if ($request->page) {
            if ($request->parent_id) {
                $attributeTyres = AttributeTyre::where('attribute_id', 5)
                    ->where('parent_id', $request->parent_id)
                    ->get();
            } else {
                $attributeTyres = AttributeTyre::where('attribute_id', 5)
                    ->get();
            }
        } else {
            if ($request->season_id != null) {
                $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                    ->where('type_id', $request->type_id)
                    ->where('attribute_id', 5)
                    ->get();
            }
            if ($request->season_id != null && $request->parent_id != null) {
                $attributeTyres = AttributeTyre::where('season_id', $request->season_id)
                    ->where('type_id', $request->type_id)
                    ->where('parent_id', $request->parent_id)
                    ->where('attribute_id', 5)
                    ->get();
            } else {
                $attributeTyres = AttributeTyre::where('type_id', $request->type_id)
                    ->where('attribute_id', 5)
                    ->get();
            }
        }
        return $this->response(
            true,
            200,
            __('Data has been get successfully'),
            $attributeTyres
        );
    }
    public function approvedStatus(AttributeTyre $attributeTyre)
    {
        if ($attributeTyre->status == 0) {
            $approved = 1;
        } elseif ($attributeTyre->status == 1) {
            $approved = 0;
        }
        $updateData = $attributeTyre->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.attribute-tyre.index')
            ]
        );
    }
}
