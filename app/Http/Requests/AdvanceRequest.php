<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdvanceRequest extends FormRequest
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
            'user_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'note' => 'nullable',
            'approved' => 'required|in:0,1,2',
        ];
    }

    protected function update(): array
    {
        return [
            'user_id' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'note' => 'nullable',
            'approved' => 'required|in:0,1,2',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trns('input_name_must_be_required'),
            'hours.required' => trns('input_hours_must_be_required'),
            'advances.required' => trns('input_advances_must_be_required'),
        ];
    }
}
