<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarModelFormRequest extends FormRequest
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
                        'car_made_id' => 'required|int|exists:car_mades,id',
                        'name_ar' =>'required|unique:car_models,name_ar',
                        'name_en' =>'required|unique:car_models,name_en',                      
                      
                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'car_made_id' => 'required|int|exists:car_mades,id',
                        'name_ar' => 'required|unique:car_models,name_ar,'.$this->segment(3),
                        'name_en' => 'required|unique:car_models,name_en,'.$this->segment(3),
                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
