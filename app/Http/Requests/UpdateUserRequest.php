<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role->name == 'Admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$this->user->id,
            'role_id' => 'required',
            'status' => 'required',
            'department_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn phải nhập tên.',
            'email.required' => 'Bạn phải nhập email.',
            'email.unique' => 'Email bị trùng',
            'role_id.required' => 'Bạn phải chọn vai trò.',
            'status.required' => 'Bạn phải chọn trạng thái.',
            'department_id.required' => 'Bạn phải chọn phòng/ban.',
        ];
    }
}
