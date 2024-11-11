<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFamilyRequest extends FormRequest
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
            'employee_id' => 'required',
            'name' => 'required',
            'year_of_birth' => 'required',
            'job' => 'required',
            'type' => 'required',
            'health' => 'required',
            'is_living_together' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Số id nhân viên không đúng.',
            'name.required' => 'Bạn cần nhập tên.',
            'year_of_birth.required' => 'Bạn cần nhập năm sinh.',
            'job.required' => 'Bạn cần nhập nghề nghiệp.',
            'type.required' => 'Bạn cần nhập quan hệ.',
            'health.required' => 'Bạn cần nhập sức khỏe.',
            'is_living_together.required' => 'Bạn cần nhập sống cùng.',
        ];
    }
}
