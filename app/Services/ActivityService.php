<?php

namespace App\Services;

use App\Models\Activity;
use App\Traits\UploadImageTrait;

class ActivityService
{
    use UploadImageTrait;

    public function listPaginated()
    {
        return Activity::with('destination')
            ->latest()
            ->paginate(10);
    }

    public function create(array $data)
    {
        // عيّن القيمة الافتراضية للتقييم عند عدم الإرسال
        if (!array_key_exists('rating', $data) || $data['rating'] === null || $data['rating'] === '') {
            $data['rating'] = 0;
        }

        // معالجة الحقول الجديدة
        $data['is_featured'] = isset($data['is_featured']) && $data['is_featured'] == '1';
        $data['is_must_visit'] = isset($data['is_must_visit']) && $data['is_must_visit'] == '1';
        $data['is_event'] = isset($data['is_event']) && $data['is_event'] == '1';
        // إذا لم يكن فعالية، احذف event_date
        if (!($data['is_event'] ?? false)) {
            $data['event_date'] = null;
        } else {
            $data['event_date'] = isset($data['event_date']) && !empty($data['event_date']) ? $data['event_date'] : null;
        }
        $data['requires_booking'] = isset($data['requires_booking']) && $data['requires_booking'] == '1';
        $data['duration_minutes'] = isset($data['duration_minutes']) && !empty($data['duration_minutes']) ? (int)$data['duration_minutes'] : null;
        $data['duration_label'] = isset($data['duration_label']) && !empty($data['duration_label']) ? $data['duration_label'] : null;

        // معالجة الأقسام المخصصة - إزالة الأقسام الفارغة
        if (isset($data['custom_sections_ar']) && is_array($data['custom_sections_ar'])) {
            $data['custom_sections_ar'] = array_values(array_filter($data['custom_sections_ar'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($data['custom_sections_ar'])) {
                $data['custom_sections_ar'] = null;
            }
        } else {
            $data['custom_sections_ar'] = null;
        }

        if (isset($data['custom_sections_en']) && is_array($data['custom_sections_en'])) {
            $data['custom_sections_en'] = array_values(array_filter($data['custom_sections_en'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($data['custom_sections_en'])) {
                $data['custom_sections_en'] = null;
            }
        } else {
            $data['custom_sections_en'] = null;
        }

        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'activities');
        }

        $pid = $data['provider_id'] ?? null;
        $data['provider_id'] = ! empty($pid) ? (int) $pid : null;
        $data['provider_review_status'] = $data['provider_review_status'] ?? 'approved';
        $data['provider_reviewed_at'] = ($data['provider_review_status'] ?? 'approved') !== 'pending_review' ? now() : null;

        return Activity::create($data);
    }

    public function update(Activity $activity, array $data)
    {
        // عيّن القيمة الافتراضية للتقييم عند عدم الإرسال
        if (!array_key_exists('rating', $data) || $data['rating'] === null || $data['rating'] === '') {
            $data['rating'] = 0;
        }

        // معالجة الحقول الجديدة
        $data['is_featured'] = isset($data['is_featured']) && $data['is_featured'] == '1';
        $data['is_must_visit'] = isset($data['is_must_visit']) && $data['is_must_visit'] == '1';
        $data['is_event'] = isset($data['is_event']) && $data['is_event'] == '1';
        // إذا لم يكن فعالية، احذف event_date
        if (!($data['is_event'] ?? false)) {
            $data['event_date'] = null;
        } else {
            $data['event_date'] = isset($data['event_date']) && !empty($data['event_date']) ? $data['event_date'] : null;
        }
        $data['requires_booking'] = isset($data['requires_booking']) && $data['requires_booking'] == '1';
        $data['duration_minutes'] = isset($data['duration_minutes']) && !empty($data['duration_minutes']) ? (int)$data['duration_minutes'] : null;
        $data['duration_label'] = isset($data['duration_label']) && !empty($data['duration_label']) ? $data['duration_label'] : null;

        // معالجة الأقسام المخصصة - إزالة الأقسام الفارغة
        if (isset($data['custom_sections_ar']) && is_array($data['custom_sections_ar'])) {
            $data['custom_sections_ar'] = array_values(array_filter($data['custom_sections_ar'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($data['custom_sections_ar'])) {
                $data['custom_sections_ar'] = null;
            }
        } else {
            $data['custom_sections_ar'] = null;
        }

        if (isset($data['custom_sections_en']) && is_array($data['custom_sections_en'])) {
            $data['custom_sections_en'] = array_values(array_filter($data['custom_sections_en'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($data['custom_sections_en'])) {
                $data['custom_sections_en'] = null;
            }
        } else {
            $data['custom_sections_en'] = null;
        }

        if (isset($data['image'])) {
            $this->deleteImage($activity->image);
            $data['image'] = $this->uploadImage($data['image'], 'activities');
        }

        $pid = $data['provider_id'] ?? null;
        $data['provider_id'] = ! empty($pid) ? (int) $pid : null;
        $data['provider_review_status'] = $data['provider_review_status'] ?? $activity->provider_review_status;
        $data['provider_reviewed_at'] = ($data['provider_review_status'] ?? $activity->provider_review_status) !== 'pending_review'
            ? now()
            : null;

        $activity->update($data);

        return $activity;
    }

    public function delete(Activity $activity)
    {
        $this->deleteImage($activity->image);
        return $activity->delete();
    }
}
