<?php

namespace Codeplx\LaravelCodeplxSupport\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CodeplxSupportAPIService
{
    protected $client;
    public $agent = 'Codeplx Laravel API Client';
    public $endpoint = 'https://support.test/api';
    public $api_key;
    public $debug_data;
    public $cache_name = 'codeplx-support-api-categories';

    public function __construct()
    {
        $this->api_key = env('CODEPLX_API_KEY');
        $this->debug_data = [
            'app' => config('app.name'),
            'version' => config('app.version'),
            'env' => config('app.env'),
            'url' => config('app.url'),
        ];

        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'verify' => false, // Ignore SSL certificate verification
            'headers' => [
                'User-Agent' => $this->agent,
                'Accept' => 'application/json',
                'X-API-KEY' => $this->api_key,
            ],
        ]);
    }

    public function getStatuses()
    {
        //Cache::forget($this->cache_name);

        // Check if cache exists
        if (Cache::has($this->cache_name)) {
            return Cache::get($this->cache_name);
        }

        // Get the data
        $response = $this->client->get('api/config/categories');

        // Check if the response is successful
        if ($response->getStatusCode() !== 200) {
            return $response->getBody()->getContents();
        }

        // Cache the data
        Cache::put($this->cache_name, $response->getBody()->getContents(), 1440);

        // Return the data
        return $response->getBody()->getContents();
    }

    public function sendRequest($method, $uri, $data = [])
    {
        try {
            $response = $this->client->request($method, $uri, [
                'form_params' => $data,
            ]);

            return $response;
        } catch (\Exception $e) {
            // Return the exception as an object
            return (object) [
                'error' => $e->getMessage(),
            ];
        }
    }
}
