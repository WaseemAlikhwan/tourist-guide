<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestinationRequest extends FormRequest
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
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'country_ar' => 'required|string|max:255',
            'country_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image' => $this->isMethod('post')
                ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
                : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'custom_sections_ar' => 'nullable|array',
            'custom_sections_ar.*.title' => 'required_with:custom_sections_ar|string|max:255',
            'custom_sections_ar.*.content' => 'required_with:custom_sections_ar|string',
            'custom_sections_en' => 'nullable|array',
            'custom_sections_en.*.title' => 'required_with:custom_sections_en|string|max:255',
            'custom_sections_en.*.content' => 'required_with:custom_sections_en|string',
        ];
    }
}
