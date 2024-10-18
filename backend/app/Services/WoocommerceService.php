<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class WoocommerceService
{
    protected $client;
    protected $baseUrl;
    protected $consumerKey;
    protected $consumerSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.woocommerce.api_url');
        $this->consumerKey = config('services.woocommerce.consumer_key');
        $this->consumerSecret = config('services.woocommerce.consumer_secret');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'auth' => [$this->consumerKey, $this->consumerSecret],
            'timeout' => 30.0,
        ]);
    }

    public function fetchOrders($fromDate)
    {
        try {
            $response = $this->client->get('orders', [
                'query' => [
                    'after' => $fromDate,
                    'per_page' => 10
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('Woocommerce API Error: ' . $e->getMessage());
            return null;
        }
    }
}
