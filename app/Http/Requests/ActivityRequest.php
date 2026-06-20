<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        // ضبط الصلاحيات لاحقاً حسب الأدوار إن لزم
        return true;
    }

    public function rules(): array
    {
        return [
            'destination_id' => 'required|exists:destinations,id',
            'provider_id'    => 'nullable|exists:users,id',
            'provider_review_status' => 'nullable|in:pending_review,approved,rejected',
            'name_ar'        => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'type_ar'        => 'required|string|max:255',
            'type_en'        => 'nullable|string|max:255',
            'price'          => 'nullable|numeric|min:0',
            'rating'         => 'nullable|numeric|min:0|max:5',
            'location_ar'    => 'nullable|string|max:255',
            'location_en'    => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured'    => 'nullable|boolean',
            'is_must_visit'  => 'nullable|boolean',
            'is_event'       => 'nullable|boolean',
            'event_date'     => 'nullable|date|required_if:is_event,1',
            'requires_booking' => 'nullable|boolean',
            'duration_minutes' => 'nullable|integer|min:0',
            'duration_label' => 'nullable|string|max:255',
            'highlights_ar' => 'nullable|string',
            'highlights_en' => 'nullable|string',
            'whats_included_ar' => 'nullable|string',
            'whats_included_en' => 'nullable|string',
            'whats_not_included_ar' => 'nullable|string',
            'whats_not_included_en' => 'nullable|string',
            'additional_info_ar' => 'nullable|string',
            'additional_info_en' => 'nullable|string',
            'payment_policy_ar' => 'nullable|string',
            'payment_policy_en' => 'nullable|string',
            'cancellation_policy_ar' => 'nullable|string',
            'cancellation_policy_en' => 'nullable|string',
            'custom_sections_ar' => 'nullable|array',
            'custom_sections_ar.*.title' => 'required_with:custom_sections_ar|string|max:255',
            'custom_sections_ar.*.content' => 'required_with:custom_sections_ar|string',
            'custom_sections_en' => 'nullable|array',
            'custom_sections_en.*.title' => 'required_with:custom_sections_en|string|max:255',
            'custom_sections_en.*.content' => 'required_with:custom_sections_en|string',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $id = $this->input('provider_id');
            if (empty($id)) {
                return;
            }
            $user = User::find($id);
            if (! $user || ! $user->isApprovedContentProvider()) {
                $v->errors()->add('provider_id', 'يجب اختيار مزوّد محتوى معتمد فقط.');
            }
        });
    }
}
