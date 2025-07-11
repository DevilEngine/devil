<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StealthExService
{
    private string $apiUrl = 'https://api.stealthex.io/v4';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('STEALTHEX_API_KEY');
    }

    /**
     * Create a new exchange transaction.
     */
    public function createExchange(float $amount, string $fromCurrency, string $toCurrency, string $address, string $refundAddress, float $fee_additional, string $network_from, string $network_to)
    {

        $exchangeData = [
            'route' => [
                'from' => ['symbol' => $fromCurrency, 'network' => $network_from],
                'to' => ['symbol' => $toCurrency, 'network' => $network_to]
            ],
            'amount' => $amount,
            'estimation' => 'direct',
            'rate' => 'floating',
            'address' => $address,
            'refund_address' => $refundAddress,
            'additional_fee_percent' => $fee_additional,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post("{$this->apiUrl}/exchanges", $exchangeData);

            Log::info('API Response:', ['body' => $response->body()]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('StealthEx API Error: ' . $e->getMessage());
            return ['error' => 'Unable to process the request.'];
        }
    }

    public function createExchangeFixed(float $amount, string $rate_id, string $fromCurrency, string $toCurrency, string $address, string $refundAddress, string $network_from, string $network_to, float $fee_additional)
    {

        $exchangeData = [
            'route' => [
                'from' => ['symbol' => $fromCurrency, 'network' => $network_from],
                'to' => ['symbol' => $toCurrency, 'network' => $network_to]
            ],
            'amount' => $amount,
            'estimation' => 'reversed',
            'rate' => 'fixed',
            'rate_id' => $rate_id,
            'address' => $address,
            'refund_address' => $refundAddress,
            'additional_fee_percent' => $fee_additional,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post("{$this->apiUrl}/exchanges", $exchangeData);

            Log::info('API Response:', ['body' => $response->body()]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('StealthEx API Error: ' . $e->getMessage());
            return ['error' => 'Unable to process the request.'];
        }
    }

    /**
     * Retrieve the details of an exchange transaction.
     */
    public function getExchange(string $exchangeId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->apiUrl}/exchanges/{$exchangeId}");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('StealthEx API Exchange Fetch Error: ' . $e->getMessage());
            return ['error' => 'Unable to retrieve exchange details.'];
        }
    }

    /**
     * Retrieve the status of an exchange transaction.
     */
    public function getExchangeStatus(string $exchangeId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->apiUrl}/exchange/{$exchangeId}/status");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('StealthEx API Status Check Error: ' . $e->getMessage());
            return ['error' => 'Unable to retrieve exchange status.'];
        }
    }

    /**
     * Get a list of available cryptocurrencies.
     */
    public function getAvailableCurrencies(string $network)
    {
        try {

            $params = [
                'limit' => '250',
                'network' => $network,
            ];

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->apiUrl}/currencies", $params);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('StealthEx API Currencies Fetch Error: ' . $e->getMessage());
            return ['error' => 'Unable to retrieve available currencies.'];
        }
    }

    /**
     * Get details of a specific cryptocurrency.
     */
    public function getCurrencyDetails(string $currencySymbol, string $currencyNetwork)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->apiUrl}/currencies/{$currencySymbol}/{$currencyNetwork}");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('StealthEx API Currency Fetch Error: ' . $e->getMessage());
            return ['error' => 'Unable to retrieve currency details.'];
        }
    }

    /**
     * Get a list of all exchanges.
     */
    public function getExchangeList()
    {

        $exchangeData = [
            'limit' => 50,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->apiUrl}/exchanges", $exchangeData);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('StealthEx API Exchange List Fetch Error: ' . $e->getMessage());
            return ['error' => 'Unable to retrieve exchange list.'];
        }
    }

    /**
     * Get the exchange range between two currencies.
     */
    public function getExchangeRange(string $fromCurrency, string $toCurrency, string $market, string $network_from, string $network_to)
    {
        
        $params = [
            'route' => [
                'from' => ['symbol' => $fromCurrency, 'network' => 'mainnet'],
                'to' => ['symbol' => $toCurrency, 'network' => 'mainnet']
            ],
            'estimation' => 'direct',
            'rate' => 'floating',
            'market' => $market
        ];
        
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->post("{$this->apiUrl}/rates/range", $params);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('StealthEx API Exchange Range Fetch Error: ' . $e->getMessage());
            return ['error' => 'Unable to retrieve exchange range.'];
        }
    }

    public function getEstimatedExchangeAmount(string $fromCurrency, string $toCurrency, float $amount, float $fee_additional, string $network_from, string $network_to, string $estimation, string $rate)
    {
        
        $params = [
            'route' => [
                'from' => ['symbol' => $fromCurrency, 'network' => $network_from],
                'to' => ['symbol' => $toCurrency, 'network' => $network_to],
            ],
            'estimation' => $estimation,
            'rate' => $rate,
            'amount' => $amount,
            'additional_fee_percent' => $fee_additional
        ];
        
        try {

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->post("{$this->apiUrl}/rates/estimated-amount", $params);

            Log::info('API Response:', ['body' => $response->body()]);

            $data = $response->json();

            Log::info('StealthEx API Estimated Amount Response:', ['response' => $data]);

            return $data;
        } catch (\Exception $e) {
            Log::error('StealthEx API Estimated Exchange Amount Fetch Error:', ['message' => $e->getMessage()]);
            return ['error' => 'Unable to retrieve estimated exchange amount.'];
        }
    }

    public function getEstimatedExchangeAmountFixed(string $fromCurrency, string $toCurrency, float $amount, string $network_from, string $network_to, string $estimation, string $rate, float $fee_additional)
    {
        
        $params = [
            'route' => [
                'from' => ['symbol' => $fromCurrency, 'network' => $network_from],
                'to' => ['symbol' => $toCurrency, 'network' => $network_to],
            ],
            'estimation' => $estimation,
            'rate' => $rate,
            'amount' => $amount,
            'additional_fee_percent' => $fee_additional,
        ];
        
        try {

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->post("{$this->apiUrl}/rates/estimated-amount", $params);

            Log::info('API Response:', ['body' => $response->body()]);

            $data = $response->json();

            Log::info('StealthEx API Estimated Amount Response:', ['response' => $data]);

            return $data;
        } catch (\Exception $e) {
            Log::error('StealthEx API Estimated Exchange Amount Fetch Error:', ['message' => $e->getMessage()]);
            return ['error' => 'Unable to retrieve estimated exchange amount.'];
        }
    }
    

    public function getFinishedExchanges()
    {

        $params = [
            'limit' => 250,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json'
            ])->get("{$this->apiUrl}/exchanges", $params);

            $data = $response->json();

            if (!isset($data['exchanges'])) {
                Log::error('Invalid API response from StealthEx: ' . json_encode($data));
                return [];
            }
            
            return array_filter($data['exchanges'], function ($exchange) {
                return $exchange['status'] === 'finished';
            });
        } catch (\Exception $e) {
            Log::error('StealthEx API Fetch Error:', ['message' => $e->getMessage()]);
            return [];
        }
    }

}