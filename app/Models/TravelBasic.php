<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLocalizedAttributes;

class TravelBasic extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'title_ar',
        'title_en',
        'icon',
        'content_ar',
        'content_en',
        'items_ar',
        'items_en',
        'custom_sections_ar',
        'custom_sections_en',
        'order',
        'is_active',
    ];

    protected $casts = [
        'items_ar' => 'array',
        'items_en' => 'array',
        'custom_sections_ar' => 'array',
        'custom_sections_en' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function getTitleAttribute(): ?string
    {
        return $this->localizedValue('title');
    }

    public function getContentAttribute(): ?string
    {
        return $this->localizedValue('content');
    }

    public function getItemsAttribute(): ?array
    {
        return $this->localizedValue('items');
    }

    public function getCustomSectionsAttribute(): ?array
    {
        return $this->localizedValue('custom_sections');
    }

    /**
     * Scope للحصول على الأساسيات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope لترتيب الأساسيات
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }
}
