<?php

namespace App\Services;

use App\Models\Destination;
use App\Traits\UploadImageTrait;

class DestinationService
{
    use UploadImageTrait;

    public function listPaginated()
    {
        return Destination::withCount('activities')
            ->latest()
            ->paginate(10);
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'destinations');
        }

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

        return Destination::create($data);
    }

    public function update(Destination $destination, array $data)
    {
        if (isset($data['image'])) {
            // حذف القديمة
            $this->deleteImage($destination->image);

            // رفع الجديدة
            $data['image'] = $this->uploadImage($data['image'], 'destinations');
        }

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

        $destination->update($data);

        return $destination;
    }

    public function delete(Destination $destination)
    {
        $this->deleteImage($destination->image);
        return $destination->delete();
    }
}
