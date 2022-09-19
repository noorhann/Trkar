<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
// use App;
use App\Helpers\Helper;
use App\Http\Requests\ProductFormRequest;
use App\Models\CarEngine;
use App\Models\CarMade;
use App\Models\CarModel;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductTag;
use App\Models\ProductView;
use App\Models\Year;
use App\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;

class ProductController extends SystemController
{
    public function index(ProductFormRequest $request)
    {
        if ($request->isTablePagination) {
            $eloquentData =  Product::select([
                'id',
                'uuid',
                'slug',
                'car_model_id',
                'car_engine_id',
                'subcategory_id',
                'status',
                'product_type_id',
                'serial_number',
                'name_en',
                'name_ar',
                'details_en',
                'details_ar',
                'price',
                'discount',
                'image',
                'category_id',
                'car_made_id',
                'year_id',
                'manufacturer_id',
                'original_country_id',
                'store_id',
                'approved',
                'OEN',
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
                        ->orWhere('price', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('discount', 'LIKE', '%' . $request->name . '%')
                        ->orWhere('price', 'LIKE', '%' . $request->name . '%')
                        ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        })
                        ->orWhereHas('store', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%')
                                ->orWhereHas('vendor', function ($q) use ($request) {
                                    $q->where('username', 'LIKE', '%' . $request->name . '%');
                                });
                        })

                        ->orWhereHas('subcategory', function ($q) use ($request) {
                            $q->where('name_ar', 'LIKE', '%' . $request->name . '%')
                                ->orWhere('name_en', 'LIKE', '%' . $request->name . '%');
                        });
                });
            }
            $eloquentData->orderBy('id', 'DESC');
            return Helper::tablePagination()
                ->eloquent($eloquentData)
                ->setHeadColumns([
                    __('Serial Number'),
                    __('OEN'),
                    __('Categories'),
                    __('Name (Ar)'),
                    __('Name (En)'),
                    __('Price'),
                    __('Discount'),
                    __('Store'),
                    __('Vendor'),
                    __('Status'),
                    __('Block'),
                    __('Action')

                ])

                ->addColumn('serial_number')
                ->addColumn('OEN')

                ->addColumn('subcategory_id', function ($data) {
                    $arrays = [];
                    if (isset($data->subCategory)) {
                        $parents = Category::find($data->subcategory_id)->parents->reverse();
                        foreach ($parents as $parent) {
                            if ($data->subcategory->id != $parent->id) {
                                $arrays[] = '<span style="float: right;"> - ' .$parent->{'name_' . App::getLocale()} .'</span>';

                            }
                        }
                        return implode('<br>', $arrays);
                    } else {
                        return '--';
                    }
                })
                // ->addColumn('subcategory_id', function ($data) {
                //     if (isset($data->subCategory)) {
                //         return $data->subCategory->{'name_' . App::getLocale()};
                //     } else {
                //         return '--';
                //     }
                // })
                ->addColumn('name_ar')
                ->addColumn('name_en')
                ->addColumn('price')
                ->addColumn('discount')
                ->addColumn('store_id', function ($data) {
                    if (isset($data->store)) {
                        return $data->store->{'name_' . App::getLocale()};
                    } else {
                        return '--';
                    }
                })
                ->addColumn('vendor', function ($data) {
                    if (isset($data->store->vendor)) {
                        return $data->store->vendor->username;
                    } else {
                        return '--';
                    }
                })

                ->addColumn('approved', function ($data) {
                    if (Helper::adminCan('admin.product.approved')) {
                        if ($data->status == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approvedStatus(\'' . route('admin.product.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->status == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approvedStatus(\'' . route('admin.product.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('block', function ($data) {
                    if (Helper::adminCan('admin.product.approved')) {
                        if ($data->approved == 1) {
                            return '<div class="custom-control custom-switch">
                        <input checked onchange="approved(\'' . route('admin.product.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        } elseif ($data->approved == 0) {
                            return '<div class="custom-control custom-switch">
                        <input  onchange="approved(\'' . route('admin.product.approved', $data->id) . '\');"  type="checkbox" class="custom-control-input" id="' . $data->id . '">
                        <label class="custom-control-label" for="' . $data->id . '"></label>';
                        }
                    } else {
                        return 'you do not have permission';
                    }
                })
                ->addColumn('action', function ($data) {
                    $show = '';

                    if (Helper::adminCan('admin.product.show')) {
                        $show = ' <a href="' . route('admin.product.show', $data->id) . '" class="action-icon"> <i class="mdi mdi-eye-circle-outline"></i></a>';
                    }
                    return  $show;
                })
                ->render($request->items_per_page);
        } else {
            // View Data
            $this->viewData['breadcrumb'][] = [
                'text' => __('products')
            ];

            $this->viewData['pageTitle'] = __('products');

            return $this->view('product.index', $this->viewData);
        }
    }

    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Product'),
            'url' => route('admin.product.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Create Product'),
        ];

        $this->viewData['pageTitle'] = __('Create Product');

        return $this->view('product.create', $this->viewData);
    }

    public function store(ProductFormRequest $request)
    {
        $requestData = $request->all();

        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("products",  $request->file('image'));
        }

        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();
        if ($requestData['product_type_id'] == 1) {
            $requestData['uuid'] = 'VR' . '_' . \Auth::user()->id . '_' . $request->serial_number;
        }
        if ($requestData['product_type_id']  == 2) {
            $requestData['uuid'] = 'VW' . '_' . \Auth::user()->id . '_' . $request->serial_number;
        }
        $requestData['uuid'] = 'VW' . '_' . \Auth::user()->id . '_' . $request->serial_number;

        // save tags


        $insertData = Product::create($requestData);
        if ($insertData) {
            $arrayTags = explode(',', $requestData['tags']);
            foreach ($arrayTags as $key => $value) {
                $insertTags = ProductTag::create([
                    'name' => $value,
                    'product_id' => $insertData->id
                ]);
            }
            if (isset($requestData['images'])) {
                foreach ($requestData['images'] as $key => $value) {
                    $image =  Storage::disk('public')->put("product-images",  $request->file('images.' . $key));
                    $insertImage = ProductImage::create([
                        'image' => $image,
                        'product_id' => $insertData->id
                    ]);
                }
            }
            if (isset($requestData['attributes_tiers'])) {
                foreach ($requestData['images'] as $key => $value) {
                    $image =  Storage::disk('public')->put("product-images",  $request->file('images.' . $key));
                    $insertImage = ProductImage::create([
                        'image' => $image,
                        'product_id' => $insertData->id
                    ]);
                }
            }
            return $this->response(
                true,
                200,
                __('Data has been added successfully'),
                [
                    'url' => route('admin.product.create')
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
    public function edit(Product $product)
    {

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Product'),
            'url' => route('admin.product.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text' => __('Edit (:name)', ['name' => $product->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Edit Product');
        $this->viewData['result'] = $product;

        return $this->view('product.create', $this->viewData);
    }


    public function update(ProductFormRequest $request, Product $product)
    {
        $requestData = $request->all();

        if ($request->file('image')) {
            $requestData['image'] =  Storage::disk('public')->put("categories",  $request->file('image'));
        } else {
            $requestData['image'] =  $product->image;
        }
        $requestData['slug'] = Str::slug($request->get('name_en')) . ' - ' . Helper::quickRandom();
        $updateData = $product->update($requestData);
        if ($updateData) {
            $arrayTags = explode(',', $requestData['tags']);
            foreach ($arrayTags as $key => $value) {
                $insertTags = ProductTag::create([
                    'name' => $value,
                    'product_id' => $product->id
                ]);
            }
            if (isset($requestData['images'])) {
                foreach ($requestData['images'] as $key => $value) {
                    $image =  Storage::disk('public')->put("product-images",  $request->file('images.' . $key));
                    $insertImage = ProductImage::create([
                        'image' => $image,
                        'product_id' => $product->id
                    ]);
                }
            }

            if (isset($requestData['attributes_tiers'])) {
                // dd($requestData['attributes_tiers']['value']);
                foreach ($request->attributes_tiers['key'] as $key => $value) {
                    //  dd($value);
                    if ($value != null) {
                        $insertAttribute = ProductAttribute::create([
                            'key' => $value,
                            'value' => $request->attributes_tiers['value'][$key],
                            'product_id' => $product->id
                        ]);
                    }
                }
            }
            return $this->response(
                true,
                200,
                __('Data has been modified successfully'),
                [
                    'url' => route('admin.product.index')
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

    public function show(Product $product)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text' => __('Product'),
            'url' => route('admin.product.index')
        ];


        $this->viewData['breadcrumb'][] = [
            'text' => __('Show (:name)', ['name' => $product->{'name_' . App::getLocale()}]),
        ];

        $this->viewData['pageTitle'] = __('Show Product');

        $yearsArrays = [];
        if ($product->car_made_id != null) {
            $yearDecode = json_decode($product->year_id);
            foreach (json_decode($yearDecode[0]) as $year) {
                $yearName = Year::find($year);
                $yearsArrays[] = $yearName->year;
            }
        }
        $madeArrays = [];
        if ($product->car_made_id != null) {
            $madeDecode = json_decode($product->car_made_id);
            foreach (json_decode($madeDecode[0]) as $made) {
                $madeName = CarMade::find($made);
                if ($madeName) {
                    $madeArrays[] = $madeName->{'name_' . \App::getLocale()};
                }
            }
        }
        $modelArrays = [];
        if ($product->car_model_id != null) {
            $modelDecode = json_decode($product->car_model_id);
            foreach (json_decode($modelDecode[0]) as $model) {
                $modelName = CarModel::find($model);
                if ($modelName) {
                    $modelArrays[] = $modelName->{'name_' . \App::getLocale()};
                }
            }
        }
        $engineArrays = [];
        if ($product->car_engine_id != null) {
            $engineDecode = json_decode($product->car_engine_id);
            foreach (json_decode($engineDecode[0]) as $engine) {
                $engineName = CarEngine::find($engine);
                if ($engineName) {
                    $engineArrays[] = $engineName->{'name_' . \App::getLocale()};
                }
            }
        }

        $this->viewData['yearsArrays'] = $yearsArrays;
        $this->viewData['madeArrays'] = $madeArrays;
        $this->viewData['modelArrays'] = $modelArrays;
        $this->viewData['engineArrays'] = $engineArrays;
        $this->viewData['result'] = $product;

        return $this->view('product.show', $this->viewData);
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->response(
            true,
            200,
            __('Data has been deleted successfully'),
            [
                'url' => route('admin.product.index')
            ]
        );
    }

    public function approved(Product $product)
    {
        if ($product->approved == 0) {
            $approved = 1;
        } elseif ($product->approved == 1) {
            $approved = 0;
        }
        $updateData = $product->update([
            'approved' => $approved
        ]);

        $product->update;
        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.product.index')
            ]
        );
    }
    public function approvedStatus(Product $product)
    {
        if ($product->status == 0) {
            $approved = 1;
        } elseif ($product->status == 1) {
            $approved = 0;
        }
        $updateData = $product->update([
            'status' => $approved
        ]);

        return $this->response(
            true,
            200,
            __('Data has been updatad successfully'),
            [
                'url' => route('admin.product.index')
            ]
        );
    }
}
