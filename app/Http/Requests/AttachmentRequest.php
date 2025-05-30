<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
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
        // dd($this->expirationDate);
        $rules = [
            'description_ar' => 'required|string|max:255',
            'description_en' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'expirationDate' => 'nullable|date',
            'alertable' => 'required|boolean',
            'alertBefore' => 'nullable',
            'attachable_id' => 'required|integer',
            'attachable_type' => 'required|string',

        ];
        if ($this->alertable) {
            $rules['alertBefore'] = 'required|integer|min:1';
            $rules['expirationDate'] = 'required|date';
        }
        if ($this->isMethod('put')) {
            // check if file is string
            if (is_string($this->file)) {
                $rules['file'] = 'required|string';
            } else {
                $rules['file'] = 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx';
            }
        }
        return $rules;
    }

    // prepare the request for the controller
    public function prepareForValidation()
    {
        $this->merge([
            'attachable_type' => 'App\\Models\\' . $this->input('attachable_type'),
            'alertBefore' => $this->input('alertable') == true ? $this->input('alertBefore') : null,
        ]);
    }

    public function messages()
    {
        return [
            'description_ar.required' => __('messages.description_ar_required'),
            'description_en.required' => __('messages.description_en_required'),
            'file.required' => __('messages.file_required'),
            'file.file' => __('messages.file_file'),
            'file.mimes' => __('messages.file_mimes'),
            'alertBefore.required' => __('messages.alertBefore_required'),
            'alertBefore.integer' => __('messages.alertBefore_integer'),
            'alertBefore.min' => __('messages.alertBefore_min'),
            'expirationDate.required' => __('messages.expirationDate_required'),
            'expirationDate.date' => __('messages.expirationDate_date'),
        ];
    }
}