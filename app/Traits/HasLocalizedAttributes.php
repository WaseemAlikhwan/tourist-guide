<?php

namespace App\Traits;

trait HasLocalizedAttributes
{
    protected function localizedValue(string $base): mixed
    {
        $locale = app()->getLocale() === 'en' ? 'en' : 'ar';
        $primary = $this->getAttributeValue($base . '_' . $locale);
        if ($this->isFilled($primary)) {
            return $primary;
        }

        // Strict English mode: do not fall back to Arabic when EN content is missing.
        if ($locale === 'en') {
            return null;
        }

        $fallbackLocale = $locale === 'en' ? 'ar' : 'en';
        $fallback = $this->getAttributeValue($base . '_' . $fallbackLocale);
        if ($this->isFilled($fallback)) {
            return $fallback;
        }

        return null;
    }

    private function isFilled(mixed $value): bool
    {
        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return !empty($value);
        }

        return $value !== null;
    }
}
