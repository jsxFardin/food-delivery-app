<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiderLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rider_id' => 'required|numeric',
            'lat' => 'required',
            'long' => 'required',
            'service_name' => 'required',
            'capture_time' => 'required',
        ];
    }
}
