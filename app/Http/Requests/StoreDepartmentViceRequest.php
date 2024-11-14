<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDepartmentViceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Nhân sự' == Auth::user()->role->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'department_id' => 'required|unique:department_vices',
            'vice_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.required' => 'Bạn phải chọn phòng/ban.',
            'department_id.unique' => 'Phòng/ban đã có phó phòng.',
            'vice_id.required' => 'Bạn phải chọn phó phòng.',
        ];
    }
}
