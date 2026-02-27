<?php

namespace App\Infrastructure\Http;

use Exception;

class RestClient {
    private string $baseUrl;
    private array $defaultHeaders = [];
    private int $timeout = 30;

    public function __construct(string $baseUrl, array $defaultHeaders = []) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->defaultHeaders = $defaultHeaders;
    }

    public function setTimeout(int $seconds): void {
        $this->timeout = $seconds;
    }

    public function get(string $endpoint, array $queryParams = [], array $headers = []): HttpResponse {
        return $this->request('GET', $endpoint, null, $queryParams, $headers);
    }

    public function post(string $endpoint, array $data = [], array $headers = []): HttpResponse {
        return $this->request('POST', $endpoint, $data, [], $headers);
    }

    public function put(string $endpoint, array $data = [], array $headers = []): HttpResponse {
        return $this->request('PUT', $endpoint, $data, [], $headers);
    }

    public function delete(string $endpoint, array $headers = []): HttpResponse {
        return $this->request('DELETE', $endpoint, null, [], $headers);
    }

    private function request(
        string $method,
        string $endpoint,
        ?array $data = null,
        array $queryParams = [],
        array $headers = []
    ): HttpResponse {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        if (!empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        $ch = curl_init($url);

        $mergedHeaders = array_merge($this->defaultHeaders, $headers);

        $formattedHeaders = [];
        foreach ($mergedHeaders as $key => $value) {
            $formattedHeaders[] = "$key: $value";
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $formattedHeaders,
            CURLOPT_TIMEOUT => $this->timeout,
        ]);

        // **Damit CURLINFO_HEADER_OUT funktioniert**
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        if ($data !== null) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        }

        $responseBody = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $sentHeadersString = curl_getinfo($ch, CURLINFO_HEADER_OUT) ?: '';
        $sentHeadersArray = array_filter(array_map('trim', explode("\r\n", $sentHeadersString)));

        if ($responseBody === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: $error");
        }

        curl_close($ch);

        return new HttpResponse(
            $httpCode,
            $sentHeadersArray,
            $responseBody
        );
    }
}