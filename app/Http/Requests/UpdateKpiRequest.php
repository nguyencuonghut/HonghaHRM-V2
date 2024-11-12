<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateKpiRequest extends FormRequest
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
            'year' => 'required',
            'month' => 'required',
            'score' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'year.required' => 'Bạn cần nhập năm.',
            'month.required' => 'Bạn cần nhập tháng.',
            'score.required' => 'Bạn cần nhập điểm.',
        ];

    }
}
