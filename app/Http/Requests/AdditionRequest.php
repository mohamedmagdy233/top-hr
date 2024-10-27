<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdditionRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
       if (request()->isMethod('put')) {
           return $this->update();
       } else {
           return $this->store();
       }
    }

    public function update(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'reason' => 'required',
            'value' => 'required|numeric',
        ];

    }

    public function store(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'reason' => 'required',
            'value' => 'required|numeric',
        ];

    }
}
