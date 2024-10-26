<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Nhân sự' == Auth::user()->role->nam;
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
            'current_salary' => 'required',
            'desired_salary' => 'required',
            'insurance_salary' => 'required',
            'position_salary' => 'required',
            'capacity_salary' => 'required',
            'position_allowance' => 'required',
            'feedback' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'recruitment_candidate_id.required' => 'Số phiếu đề nghị tuyển dụng không hợp lệ.',
            'current_salary.required' => 'Bạn phải nhập lương hiện tại',
            'desired_salary.required' => 'Bạn phải nhập lương yêu cầu.',
            'insurance_salary.required' => 'Bạn phải nhập lương vị trí.',
            'position_salary.required' => 'Bạn phải nhập lương vị trí.',
            'capacity_salary.required' => 'Bạn phải nhập lương năng lực.',
            'position_allowance.required' => 'Bạn phải nhập phụ cấp vị trí.',
            'feedback.required' => 'Bạn phải nhập phản hồi.',
        ];
    }
}
