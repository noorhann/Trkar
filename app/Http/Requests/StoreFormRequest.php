<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
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
                        'name_ar' =>'required|unique:stores,name_ar,NULL,id,deleted_at,NULL',
                        'name_en' =>'required|unique:stores,name_en,NULL,id,deleted_at,NULL',                      
                        'email' => 'required|email|unique:stores,email,NULL,id,deleted_at,NULL',
                        'phone' =>'required|unique:stores,phone,NULL,id,deleted_at,NULL',
                        'vendor_id' => 'nullable|int|exists:vendors,id',
                        'store_type_id' => 'nullable|int|exists:store_types,id',

                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'name_ar' => 'required|unique:stores,name_ar,'.$this->segment(3).',id,deleted_at,NULL',
                        'name_en' => 'required|unique:stores,name_en,'.$this->segment(3).',id,deleted_at,NULL',                    
                       
                        'vendor_id' => 'nullable|int|exists:vendors,id',
                        'store_type_id' => 'nullable|int|exists:store_types,id',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
