<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRejectFormRequest extends FormRequest
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
                        'vendor_id' => 'required|int|exists:vendors,id',
                        'reject_status_id' => 'required|int|exists:reject_statuses,id',

                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'vendor_id' => 'required|int|exists:vendors,id',
                        'reject_status_id' => 'required|int|exists:reject_statuses,id',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
