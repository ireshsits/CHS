<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use RoleHelper;

class RegionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasAnyRole(RoleHelper::getAdminRoles());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//             'zone_id_fk' => 'required|exists:zones,zone_id_pk',
            'name' => 'required',
            'number' => [
                'required',
                Rule::unique('regions')->ignore($this->region_id_pk, 'region_id_pk')->where(function ($q) {
                    $q->whereNull('deleted_at');
                })
            ],
            'manager_id_fk' => 'required|exists:users,user_id_pk',
            'status' => 'required'
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'The :attribute is required.',
            'zone_id_fk.required' => 'Select zone.',
            'zone_id_fk.exists' => 'Selected zone not exists.',
            'manager_id_fk.required' => 'The manager is required',
            'manager_id_fk.exists' => 'Selected user not exists.',
            'number.unique' => 'Number already taken.'
        ];
    }
}
