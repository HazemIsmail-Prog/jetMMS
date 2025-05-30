<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LetterRequest extends FormRequest
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
            'type' => 'required',
            'date' => 'nullable',
            'sender' => 'required',
            'receiver' => 'required',
            'reference' => 'nullable',
            'subject' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => __('messages.type_required'),
            'sender.required' => __('messages.sender_required'),
            'receiver.required' => __('messages.receiver_required'),
            'subject.required' => __('messages.subject_required'),
        ];
    }
}