<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookingTransactionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'started_at' => 'required|date|after_or_equal:today',
            'office_space_id' => 'required|integer|exists:office_spaces,id',
            'total_amount' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',

            'phone_number.required' => 'Nomor telepon wajib diisi',
            'phone_number.string' => 'Nomor telepon harus berupa teks',
            'phone_number.max' => 'Nomor telepon maksimal 20 karakter',

            'started_at.required' => 'Tanggal mulai wajib diisi',
            'started_at.date' => 'Format tanggal tidak valid',
            'started_at.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini',

            'office_space_id.required' => 'Office space wajib dipilih',
            'office_space_id.integer' => 'Office space ID harus berupa angka',
            'office_space_id.exists' => 'Office space yang dipilih tidak ditemukan',

            'total_amount.required' => 'Total amount wajib diisi',
            'total_amount.integer' => 'Total amount harus berupa angka',
            'total_amount.min' => 'Total amount harus lebih dari 0',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
