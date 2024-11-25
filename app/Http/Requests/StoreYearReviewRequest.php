<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreYearReviewRequest extends FormRequest
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
            'year' => 'required',
            'kpi_average' => 'required',
            'result' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Số id nhân sự không đúng.',
            'position_id.required' => 'Bạn cần nhập Vị Trí.',
            'year.required' => 'Bạn cần nhập năm.',
            'kpi_average.required' => 'Bạn cần nhập tháng.',
            'result.required' => 'Bạn cần nhập kết quả.',
        ];
    }
}
