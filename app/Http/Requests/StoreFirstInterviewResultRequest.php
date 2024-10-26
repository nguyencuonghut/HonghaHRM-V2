<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFirstInterviewResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Ban lãnh đạo' == Auth::user()->role->name || 'Trưởng đơn vị' == Auth::user()->role->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recruitment_candidate_id' => 'required',
            'result' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'recruitment_candidate_id.required' => 'Số phiếu đề nghị tuyển dụng không hợp lệ.',
            'result.required' => 'Bạn phải chọn kết quả.',
        ];
    }
}
