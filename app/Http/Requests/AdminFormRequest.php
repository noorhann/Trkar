<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminFormRequest extends FormRequest
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
                        'username' => 'required|string|unique:admins,username,NULL,id,deleted_at,NULL',
                        'email' => 'required|email|unique:admins,email,NULL,id,deleted_at,NULL',
                        'password' => 'required|string|min:6',
                        'permission_group_id' => 'required|int|exists:permission_groups,id',
                        

                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'username' => 'required|string|unique:admins,username,'.$this->segment(3).',id,deleted_at,NULL',
                        'email' => 'required|email|unique:admins,email,'.$this->segment(3).',id,deleted_at,NULL',
                        'password' => 'required|string|min:6',
                        'permission_group_id' => 'required|int|exists:permission_groups,id',


                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
