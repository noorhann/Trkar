<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeTyreFormRequest extends FormRequest
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
                        'season_id' => 'nullable|int|exists:seasons,id',
                        'type_id' => 'required|int|exists:tyre_types,id',
                        'attribute_id' => 'required|int|exists:attributes,id',
                        'parent_id' => 'nullable|int',
                        'value' => 'required|string',
                      
                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'season_id' => 'nullable|int|exists:seasons,id',
                        'type_id' => 'required|int|exists:tyre_types,id',
                        'attribute_id' => 'required|int|exists:attributes,id',
                        'parent_id' => 'nullable|int',
                        'value' => 'required|string',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
