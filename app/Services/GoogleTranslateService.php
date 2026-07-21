<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleTranslateService
{
    private static string $baseUrl = 'https://translation.googleapis.com/language/translate/v2';

    public static function translate(string $text, string $target, string $source = 'en'): string
    {
        $key      = config('services.google.translate_key', '');
        $cacheKey = "translate:{$target}:" . md5($text);

        if ($cached = Cache::get($cacheKey)) {
            return $cached;
        }

        try {
            $res = Http::timeout(10)->post(self::$baseUrl . "?key={$key}", [
                'q'      => $text,
                'source' => $source,
                'target' => $target,
                'format' => 'text',
            ]);

            if ($res->successful()) {
                $translated = $res->json('data.translations.0.translatedText');
                if ($translated) {
                    Cache::put($cacheKey, $translated, now()->addDays(7));
                    return $translated;
                }
            }
        } catch (\Exception $e) {
            Log::error('Google Translate: ' . $e->getMessage());
        }

        return $text;
    }

    public static function translateBatch(array $texts, string $target): array
    {
        $key = config('services.google.translate_key', '');

        try {
            $res = Http::timeout(15)->post(self::$baseUrl . "?key={$key}", [
                'q'      => $texts,
                'source' => 'en',
                'target' => $target,
                'format' => 'text',
            ]);

            if ($res->successful()) {
                $translations = $res->json('data.translations');
                return array_column($translations ?? [], 'translatedText') ?: $texts;
            }
        } catch (\Exception $e) {
            Log::error('Google Translate batch: ' . $e->getMessage());
        }

        return $texts;
    }
}
