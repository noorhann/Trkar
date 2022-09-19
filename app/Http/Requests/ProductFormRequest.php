<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET': {
                return []; 
                }
            case 'DELETE': {
                    return [];
                }
            case 'POST': {

                    $validation = [
                        // 'parent_id' => 'nullable|int|exists:categories,id',
                        // 'name_ar' => 'required|string',
                        // 'name_en' => 'required|string',
                        // 'image' => 'required|file|mimes:jpeg,jpg,png',
                        // 'order' => 'nullable|int',

                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'parent_id' => 'nullable|int|exists:categories,id',
                        'name_ar' => 'required|string',
                        'name_en' => 'required|string',
                        'image' => 'nullable|file|mimes:jpeg,jpg,png',
                        'order' => 'nullable|int',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
