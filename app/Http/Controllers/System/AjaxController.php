<?php

namespace App\Http\Controllers\System;

use App\Models\Category;


// use Session;

class AjaxController extends SystemController
{

    public function getCategories(Request $request)
    {
        if ($request->has('term')) {
            $term = $request->term;
            $data = Category::where('name', 'like', '%' . $term . '%')
                ->pluck('name_' . App::getLocale() . ' as name', 'id')
                ->map(function ($name, $id) {
                    return [
                        'id'   => $id,
                        'text' => $name,
                    ];
                })
                ->values()
                ->all();

            return json_encode(['results' => $data]);
        }
    }
  
}
