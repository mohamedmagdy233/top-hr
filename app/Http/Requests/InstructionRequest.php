<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InstructionRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
        ];
    }

    protected function update(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => trns('input_name_must_be_required'),
            'description.required' => trns('input_address_must_be_required'),
        ];
    }
}
