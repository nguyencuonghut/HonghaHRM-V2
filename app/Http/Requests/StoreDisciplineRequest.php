<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDisciplineRequest extends FormRequest
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
            'position_id' => 'required',
            'code' => 'required|unique:disciplines',
            'dis_sign_date' => 'required',
            'dis_content' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Số id nhân viên không hợp lệ.',
            'position_id.required' => 'Bạn phải chọn Vị Trí.',
            'code.required' => 'Bạn phải nhập số kỷ luật.',
            'code.unique' => 'Số kỷ luật đã tồn tại.',
            'sigdis_sign_daten_date.required' => 'Bạn phải nhập ngày ký.',
            'dis_content.required' => 'Bạn phải nhập nội dung.',
        ];
    }
}
