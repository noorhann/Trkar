<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryFormRequest extends FormRequest
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
                        'name_ar' =>'required|unique:countries,name_ar,NULL,id,deleted_at,NULL',
                        'name_en' =>'required|unique:countries,name_en,NULL,id,deleted_at,NULL',         
                        'country_code' => 'required|string',
                        'iso3' => 'required|string',
                        'numcode' => 'required|string',
                        'phonecode' => 'required|string',
                      
                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'name_ar' =>'required|unique:countries,name_ar,'.$this->segment(3).',NULL,id,deleted_at,NULL',
                        'name_en' =>'required|unique:countries,name_en,'.$this->segment(3).',NULL,id,deleted_at,NULL',
                        'country_code' => 'required|string',
                        'iso3' => 'required|string',
                        'numcode' => 'required|string',
                        'phonecode' => 'required|string',
                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}