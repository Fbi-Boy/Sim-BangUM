<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Throwable;

class TranslateController extends Controller
{
    public function batch(Request $request)
    {
        $data = $request->validate([
            'lang'    => 'required|in:en,ar,zh',
            'texts'   => 'required|array|max:50', // Maksimal 50 teks sekali kirim
            'texts.*' => 'string|max:1000',
        ]);

        // Mapping bahasa
        $target = ['en' => 'en', 'ar' => 'ar', 'zh' => 'zh-CN'][$data['lang']];

        $out = [];
        $todo = [];

        // Cek Cache dulu
        foreach (array_unique($data['texts']) as $text) {
            $key = 'tr:' . $target . ':' . md5($text);
            $cached = Cache::get($key);

            if ($cached !== null) {
                $out[$text] = $cached;
            } else {
                $todo[$key] = $text;
            }
        }

        // Kalau ada yang belum di-cache, terjemahkan
        if (count($todo) > 0) {
            try {
                $tr = new GoogleTranslate($target);
                $tr->setSource('id'); // Bahasa asal: Indonesia

                foreach ($todo as $key => $text) {
                    $translated = $tr->translate($text);

                    if ($translated) {
                        // Simpan di cache selamanya (atau bisa set waktu misal 30 hari)
                        Cache::forever($key, $translated);
                        $out[$text] = $translated;
                    }
                }
            } catch (Throwable $e) {
                // Jika gagal (misal internet mati), abaikan
                \Log::error('Translate Error: ' . $e->getMessage());
            }
        }

        return response()->json(['translations' => $out]);
    }
}
