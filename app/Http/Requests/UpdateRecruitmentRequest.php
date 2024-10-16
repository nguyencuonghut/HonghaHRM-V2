<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecruitmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'position_id' => 'required',
            'quantity' => 'required',
            'reason' => 'required',
            'requirement' => 'required',
            'work_time' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'position_id.required' => 'Bạn phải chọn vị trí.',
            'quantity.required' => 'Bạn phải nhập số lượng.',
            'reason.required' => 'Bạn phải nhập lý do.',
            'requirement.required' => 'Bạn phải nhập yêu cầu.',
            'work_time.required' => 'Bạn phải nhập thời gian.',
        ];
    }
}
