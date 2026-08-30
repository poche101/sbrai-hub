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

        // Reuse each text's 7-day cache (same key scheme as translate()) so
        // repeat visitors and repeat page loads don't re-bill Google for
        // content that's already been translated once. Only genuinely
        // uncached texts get sent to the API.
        $cacheKeys = array_map(fn ($t) => "translate:{$target}:" . md5($t), $texts);
        $cached    = Cache::many($cacheKeys);

        $toFetch      = [];
        $toFetchIndex = [];
        foreach ($texts as $i => $text) {
            if ($cached[$cacheKeys[$i]] === null) {
                $toFetch[]      = $text;
                $toFetchIndex[] = $i;
            }
        }

        $fetched = [];
        if (!empty($toFetch)) {
            try {
                $res = Http::timeout(15)->post(self::$baseUrl . "?key={$key}", [
                    'q'      => $toFetch,
                    'source' => 'en',
                    'target' => $target,
                    'format' => 'text',
                ]);

                if ($res->successful()) {
                    $translations = $res->json('data.translations');
                    $fetched      = array_column($translations ?? [], 'translatedText');
                }
            } catch (\Exception $e) {
                Log::error('Google Translate batch: ' . $e->getMessage());
            }
        }

        $results = $cached;
        foreach ($toFetchIndex as $j => $i) {
            $translated = $fetched[$j] ?? $texts[$i]; // graceful fallback to original
            $results[$cacheKeys[$i]] = $translated;
            Cache::put($cacheKeys[$i], $translated, now()->addDays(7));
        }

        // Reassemble in original input order.
        return array_map(fn ($i) => $results[$cacheKeys[$i]] ?? $texts[$i], array_keys($texts));
    }
}
