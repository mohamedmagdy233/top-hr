<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'branch_id' => 'required',
            'group_id' => 'nullable',
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'code' => 'required',
            'salary' => 'required',
            'holidays' => 'required',
            'password' => 'required|confirmed',
            'fcm_token' => 'nullable',
            'status' => 'nullable',
            'registered_at'=> 'required'
        ];
    }

    protected function update(): array
    {
        return [
            'branch_id' => 'required',
            'group_id' => 'nullable',
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $this->user,
            'password' => 'nullable|confirmed',
            'code' => 'required',
            'salary' => 'required',
            'holidays' => 'required',
            'fcm_token' => 'nullable',
            'status' => 'nullable',
            'registered_at'=> 'required',
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
