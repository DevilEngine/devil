<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CryptoRateService
{
    protected $apiUrl = 'https://api.coingecko.com/api/v3/simple/price';
    protected $listApiUrl = 'https://api.coingecko.com/api/v3/coins/list';

    /**
     * Récupère l'ID CoinGecko correspondant à une crypto.
     */
    public function getCoinGeckoId($symbol)
    {
        // Vérifier si la correspondance est déjà en cache (24h)
        $cryptoIds = Cache::remember("crypto_ids", 1440, function () {
            $response = Http::get($this->listApiUrl);
            return $response->successful() ? collect($response->json())->pluck('id', 'symbol')->map(fn($id) => strtolower($id))->toArray() : [];
        });

        return $cryptoIds[strtolower($symbol)] ?? null;
    }

    /**
     * Récupère le taux de conversion d'une crypto en USD via CoinGecko.
     */
    public function getRate($currency)
    {
        $currency = strtolower($currency);
        $coinId = $this->getCoinGeckoId($currency);

        if (!$coinId) {
            \Log::error("Crypto inconnue ou non supportée : {$currency}");
            return 1; // Valeur par défaut si CoinGecko ne connaît pas la crypto
        }

        // Vérifier si le taux est en cache (60 min)
        return Cache::remember("crypto_rate_{$currency}", 60, function () use ($coinId, $currency) {
            $response = Http::get($this->apiUrl, [
                'ids' => $coinId,
                'vs_currencies' => 'usd'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $rate = $data[$coinId]['usd'] ?? 1;
                \Log::info("Taux récupéré : {$currency} ({$coinId}) = {$rate} USD");
                return $rate;
            }

            \Log::error("Erreur API CoinGecko pour {$currency}");
            return 1; // Valeur par défaut en cas d'échec
        });
    }
}
