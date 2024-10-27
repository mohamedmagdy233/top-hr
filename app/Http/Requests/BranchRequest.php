<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
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
        if (request()->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [
            'name' => 'required|unique:branches,name',
            'address' => 'required',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required|unique:branches,name,'. $this->branch,
            'address' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trns('input_name_must_be_required'),
            'address.required' => trns('input_address_must_be_required'),
        ];
    }
}
