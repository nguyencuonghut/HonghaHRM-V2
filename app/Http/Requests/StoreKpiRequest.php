<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreKpiRequest extends FormRequest
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
            'position_id' => 'required',
            'year' => 'required',
            'month' => 'required',
            'score' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Số id nhân sự không đúng.',
            'position_id.required' => 'Bạn cần chọn Vị Trí.',
            'year.required' => 'Bạn cần nhập năm.',
            'month.required' => 'Bạn cần nhập tháng.',
            'score.required' => 'Bạn cần nhập điểm.',
        ];
    }
}
