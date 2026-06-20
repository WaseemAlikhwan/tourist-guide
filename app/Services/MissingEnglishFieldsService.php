<?php

namespace App\Services;

class MissingEnglishFieldsService
{
    /**
     * @param array<string, mixed> $payload
     * @param array<string, string> $fieldLabels
     * @return array<int, string>
     */
    public function detect(array $payload, array $fieldLabels): array
    {
        $missing = [];

        foreach ($fieldLabels as $field => $label) {
            $value = $payload[$field] ?? null;
            if ($this->isMissing($value)) {
                $missing[] = $label;
            }
        }

        return $missing;
    }

    private function isMissing(mixed $value): bool
    {
        if (is_array($value)) {
            return count(array_filter($value, fn ($item) => !empty($item))) === 0;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        return $value === null;
    }
}
