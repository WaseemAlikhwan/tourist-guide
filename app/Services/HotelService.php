<?php

namespace App\Services;

use App\Models\Hotel;
use App\Traits\UploadImageTrait;

class HotelService
{
    use UploadImageTrait;

    public function listPaginated()
    {
        return Hotel::with('destination')
            ->latest()
            ->paginate(10);
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], 'hotels');
        }

        // معالجة المرافق - تحويل إلى JSON
        if (isset($data['amenities_ar']) && is_array($data['amenities_ar'])) {
            $data['amenities_ar'] = array_values(array_filter($data['amenities_ar']));
            if (empty($data['amenities_ar'])) {
                $data['amenities_ar'] = null;
            }
        } else {
            $data['amenities_ar'] = null;
        }

        if (isset($data['amenities_en']) && is_array($data['amenities_en'])) {
            $data['amenities_en'] = array_values(array_filter($data['amenities_en']));
            if (empty($data['amenities_en'])) {
                $data['amenities_en'] = null;
            }
        } else {
            $data['amenities_en'] = null;
        }

        // تحويل is_active
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return Hotel::create($data);
    }

    public function update(Hotel $hotel, array $data)
    {
        if (isset($data['image'])) {
            // حذف القديمة
            $this->deleteImage($hotel->image);

            // رفع الجديدة
            $data['image'] = $this->uploadImage($data['image'], 'hotels');
        }

        // معالجة المرافق - تحويل إلى JSON
        if (isset($data['amenities_ar']) && is_array($data['amenities_ar'])) {
            $data['amenities_ar'] = array_values(array_filter($data['amenities_ar']));
            if (empty($data['amenities_ar'])) {
                $data['amenities_ar'] = null;
            }
        } else {
            $data['amenities_ar'] = null;
        }

        if (isset($data['amenities_en']) && is_array($data['amenities_en'])) {
            $data['amenities_en'] = array_values(array_filter($data['amenities_en']));
            if (empty($data['amenities_en'])) {
                $data['amenities_en'] = null;
            }
        } else {
            $data['amenities_en'] = null;
        }

        // تحويل is_active
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        $hotel->update($data);

        return $hotel;
    }

    public function delete(Hotel $hotel)
    {
        $this->deleteImage($hotel->image);
        return $hotel->delete();
    }
}
