<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YearFormRequest extends FormRequest
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
                        'year' => 'required|unique:years,year,NULL,id,deleted_at,NULL',
                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'year' => 'required|unique:years,year,'.$this->segment(3).',id,deleted_at,NULL',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
