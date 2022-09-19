<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchFormRequest extends FormRequest
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
                        'name' =>'required|unique:store_branches,name,NULL,id,deleted_at,NULL',
                        'phone' =>'required|unique:store_branches,phone,NULL,id,deleted_at,NULL',
                        'address' =>'required|string',
                        'longitude' =>'required|string',
                        'latitude' =>'required|string',
                        'branch_picked_address' =>'required|string',
                        'store_id' => 'required|int|exists:stores,id',
                                              
                    ];

                    return $validation;
                }
            case 'PUT':
            case 'PATCH': {
                    $validation = [
                        'name'=> 'required|string|unique:store_branches,name,'.$this->segment(3),
                        'store_id' => 'nullable|int|exists:stores,id',
                        'address' =>'required|string',
                        'longitude' =>'required|string',
                        'latitude' =>'required|string',

                    ];

                    return $validation;
                }
            default:
                break;
        }
    }
}
