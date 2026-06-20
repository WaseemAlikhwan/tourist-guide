<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadImageTrait
{
    /**
     * رفع صورة مع إرجاع المسار النهائي
     */
    public function uploadImage($image, $folder)
    {
        return $image->store($folder, 'public');
    }

    /**
     * حذف صورة إذا كانت موجودة
     */
    public function deleteImage(?string $imagePath)
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
