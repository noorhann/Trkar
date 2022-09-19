<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorStaffFormRequest extends FormRequest
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
                        'email' => 'required|email|unique:vendor_staff,email,NULL,id,deleted_at,NULL',
                        'phone' =>'required|unique:vendor_staff,phone,NULL,id,deleted_at,NULL',
                        'vendor_id' => 'required|int|exists:vendors,id',
                        'password' => 'required|string|min:6',

                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'username' => 'required|string',
                        'email' => 'required|email|unique:vendor_staff,email,'.$this->segment(3).',id,deleted_at,NULL',
                        'phone' =>'required|unique:vendor_staff,phone,'.$this->segment(3).',id,deleted_at,NULL',
                        'vendor_id' => 'nullable|int|exists:vendors,id',
                        'password' => 'required|string|min:6',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
