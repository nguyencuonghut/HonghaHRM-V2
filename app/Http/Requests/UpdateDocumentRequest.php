<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateDocumentRequest extends FormRequest
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
            'employee_id' => 'required',
            'e_doc_type_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Số phiếu hồ sơ nhân sự không hợp lệ.',
            'e_doc_type_id.required' => 'Bạn phải chọn tên giấy tờ',
        ];
    }
}
