<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
                        'username' => 'required|string',
                        'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
                        'phone' =>'required|unique:users,phone,NULL,id,deleted_at,NULL',
                        'country_id' => 'nullable|int|exists:countries,id',
                        'city_id' => 'nullable|int|exists:cities,id',
                        'area_id' => 'nullable|int|exists:areas,id',

                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'country_id' => 'nullable|int|exists:countries,id',
                        'city_id' => 'nullable|int|exists:cities,id',
                        'area_id' => 'nullable|int|exists:areas,id',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
