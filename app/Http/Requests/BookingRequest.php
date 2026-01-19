<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'status' => 'required|in:pending,confirmed,cancelled',
            'booking_date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            '*.required_if' => 'This field is required',
            '*.required' => 'This field is required',
            '*.regex' => 'Only alphabets and digits are allowed',
            '*.max' => 'Maximum character limit is :max',
            '*.min' => 'Minimum :min characters are required',
            '*.integer' => 'Invalid data',
            '*.date_format' => 'Invalid date format',
            '*.mimes' => 'Only jpeg, png, jpg, pdf are allowed',
            'attachments.*.max' => "Maximum file size to upload is ". config('app.attachment_file_size_in_mb'),
        ];
    }
}
