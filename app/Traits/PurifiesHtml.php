<?php

namespace App\Traits;

use Mews\Purifier\Facades\Purifier;

trait PurifiesHtml
{
    /**
     * Boot the trait to sanitize attributes before saving.
     */
    public static function bootPurifiesHtml()
    {
        static::saving(function ($model) {
            if (isset($model->htmlPurifiable) && is_array($model->htmlPurifiable)) {
                foreach ($model->htmlPurifiable as $field) {
                    if (!empty($model->{$field})) {
                        if (is_array($model->{$field})) {
                            $model->{$field} = self::purifyArray($model->{$field});
                        } elseif (is_string($model->{$field})) {
                            $model->{$field} = self::purifyString($model->{$field});
                        }
                    }
                }
            }
        });
    }

    protected static function purifyArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::purifyArray($value);
            } elseif (is_string($value)) {
                $data[$key] = self::purifyString($value);
            }
        }
        return $data;
    }

    protected static function purifyString(string $value): string
    {
        if (class_exists(Purifier::class)) {
            return Purifier::clean($value);
        }
        return strip_tags($value, '<p><a><b><i><strong><em><ul><li><ol><br><h2><h3><h4><h5><h6><img><table><tr><td><th><tbody><thead><span><div><blockquote>');
    }
}
