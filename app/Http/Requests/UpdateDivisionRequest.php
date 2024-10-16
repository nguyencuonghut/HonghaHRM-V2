<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateDivisionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Nhân sự';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:divisions,name,'.$this->division->id,
            'department_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên bị trùng',
            'department_id.required' => 'Bạn phải chọn phòng/ban.',
        ];
    }
}
