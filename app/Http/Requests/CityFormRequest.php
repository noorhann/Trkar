<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityFormRequest extends FormRequest
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
                        'country_id' => 'required|int|exists:countries,id',
                        'name_ar' => 'required|string',
                        'name_en' => 'required|string',
                      
                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'country_id' => 'required|int|exists:countries,id',
                        'name_ar' => 'required|string',
                        'name_en' => 'required|string',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
