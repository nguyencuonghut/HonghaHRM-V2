<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateInsuranceRequest extends FormRequest
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
            'insurance_type_id' => 'required',
            'insurance_s_date' => 'required',
            'pay_rate' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'insurance_type_id.required' => 'Bạn phải chọn loại bảo hiểm.',
            'insurance_s_date.required' => 'Bạn phải nhập ngày bắt đầu.',
            'pay_rate.required' => 'Bạn phải nhập tỷ lệ đóng.',
        ];
    }
}
