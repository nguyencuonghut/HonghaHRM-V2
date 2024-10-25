<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreExaminationRequest extends FormRequest
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
            'recruitment_candidate_id' => 'required',
            'standard_score' => 'required',
            'candidate_score' => 'required',
            'result' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'recruitment_candidate_id.required' => 'Số phiếu đề nghị tuyển dụng không hợp lệ.',
            'standard_score.required' => 'Bạn phải nhập điểm chuẩn',
            'candidate_score.required' => 'Bạn phải nhập điểm thi.',
            'result.required' => 'Bạn phải chọn kết quả.',
        ];
    }
}
