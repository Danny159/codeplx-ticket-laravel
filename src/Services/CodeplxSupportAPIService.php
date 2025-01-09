<?php

namespace Codeplx\LaravelCodeplxSupport\Services;

use GuzzleHttp\Client;

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
        $this->endpoint = 'https://support.codeplx.com/';
        $this->api_key = config('codeplx.api_key');
        $this->debug_data = [
            'app' => config('app.name'),
            'version' => config('app.version'),
            'env' => config('app.env'),
            'url' => config('app.url'),
            'laravel' => app()->version(),
            'codeplx_version' => config('codeplx.version'),
        ];

        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'verify' => false, // Ignore SSL certificate verification
            'headers' => [
                'User-Agent' => $this->agent,
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->api_key,
                'X-Debug-Data' => json_encode($this->debug_data),
                'X-App' => config('app.url'),
            ],
        ]);
    }

    public function getCategories()
    {
        // Get the statuses from the API
        $response = $this->sendRequest('GET', 'api/config/categories');

        // Check if the response has an error
        if(isset($response->error)) {
            return $response;
        }

        // Return the response as an object
        return json_decode($response->getBody()->getContents());
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
