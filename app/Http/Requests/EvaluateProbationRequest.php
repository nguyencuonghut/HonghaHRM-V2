<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EvaluateProbationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Trưởng đơn vị' == Auth::user()->role->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'result_of_work' => 'required',
            'result_of_attitude' => 'required',
            'result_manager_status' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'result_of_work.required' => 'Bạn phải nhập kết quả công việc.',
            'result_of_attitude.required' => 'Bạn phải nhập ý thức, thái độ.',
            'result_manager_status.required' => 'Bạn phải nhập đánh giá.',
        ];
    }
}
