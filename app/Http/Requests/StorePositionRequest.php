<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePositionRequest extends FormRequest
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
            'name' => 'required|unique:positions',
            'department_id' => 'required',
            'insurance_salary' => 'required',
            'position_salary' => 'required',
            'max_capacity_salary' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Phòng/ban đã tồn tại.',
            'department_id.required' => 'Bạn phải chọn phòng/ban.',
            'insurance_salary.required' => 'Bạn phải điền lương bảo hiểm.',
            'position_salary.required' => 'Bạn phải điền lương vị trí.',
            'max_capacity_salary.required' => 'Bạn phải điền lương năng lực max',
        ];
    }
}
