<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManufacturerFormRequest extends FormRequest
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
                        'name_ar' =>'required|unique:manufacturers,name_ar,NULL,id,deleted_at,NULL',
                        'name_en' =>'required|unique:manufacturers,name_en,NULL,id,deleted_at,NULL',
                        'category_id' => 'required|array',
                        'image' => 'nullable|file|mimes:jpeg,jpg,png',
                      
                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'name_ar' =>'required|string',
                        'name_en' =>'required|string',
                        'category_id' => 'required|array',
                        'image' => 'nullable|file|mimes:jpeg,jpg,png',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
