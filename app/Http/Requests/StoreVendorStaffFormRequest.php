<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorStaffFormRequest extends FormRequest
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
                        'vendor_staff_id' => 'required|int|exists:vendor_staff,id',
                        'store_id' => 'required|int|exists:stores,id',
                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'vendor_staff_id' => 'required|int|exists:vendor_staff,id',
                        'store_id' => 'required|int|exists:stores,id',
                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
