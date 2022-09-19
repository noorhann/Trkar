<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionGroupFormRequest extends FormRequest
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
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST': {
                $validation = [
                    'name'          => 'required|string',
                    'permission_ids'   => 'required|array',
                    'permission_ids.*' => 'required|string'
                ];

                return $validation;
            }
            case 'PUT':
            case 'PATCH':{
                $validation = [
                    'name'          => 'required|string',
                    'permission_ids'   => 'required|array',
                    'permission_ids.*' => 'required|string'
                ];

                return $validation;
            }
            default:break;
        }

    }

}
