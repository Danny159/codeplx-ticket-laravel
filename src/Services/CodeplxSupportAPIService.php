<?php

namespace Codeplx\LaravelCodeplxSupport\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CodeplxSupportAPIService
{
    protected $client;
    public $agent = 'Codeplx Laravel API Client';
    public $endpoint;
    public $api_key;
    public $debug_data;
    public $cache_name = 'codeplx-support-api-categories';

    public function __construct()
    {
        $this->endpoint = config('codeplx.endpoint');
        $this->api_key = config('codeplx.api_key');
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

    public function getCategories()
    {
        try {
            // Get the statuses from the API
            $response = $this->sendRequest('GET', 'api/config/categories');

            // Return the response as an object
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return (object) [
                'error' => $e->getMessage(),
            ];
        }
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
