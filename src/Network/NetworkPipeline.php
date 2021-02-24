<?php

namespace maggz69\JengaApi\Network;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use maggz69\JengaApi\Auth\Authentication;
use maggz69\JengaApi\Auth\Certificates;

class NetworkPipeline
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var array
     */
    private array $headers = [];

    /**
     * NetworkPipeline constructor.
     *
     * @param string|null $stringToSign
     */
    public function __construct(string $stringToSign = null)
    {
        $this->client = new Client([

            'base_uri' => config('jenga.base_url'),

            'timeout' => 30,
        ]);

        if ($stringToSign) {
            $signedString = self::signString($stringToSign);
            $this->addHeader(['signature' => base64_encode($signedString)]);
        }
    }

    public static function signString($string): string
    {
        $plainText = $string;
        $privateKey = openssl_get_privatekey(Certificates::readPrivateKey());

        openssl_sign($plainText, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return $signature;
    }

    /**
     * Add a header to the request.
     *
     * This will add a header to the client that's being used to parse the request.
     *
     * @param array $headers
     */
    public function addHeader(array $headers): void
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Perform a get request on a specified resource.
     *
     * This makes use of Guzzles Get request and returns a json encoded response.
     *
     * @param string     $url
     * @param array|null $body
     *
     * @throws GuzzleException
     *
     * @return array
     */
    public function get(string $url, array $body = null)
    {
        $client = $this->client;

        $result = $client->get($url, [
            'headers' => $this->getHeaders(),
            'query'   => $body,
        ]);

        return NetworkResponse::format($result);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Perform a post request on a specified resource.
     *
     * This makes use of Guzzles post request and returns a json encoded response.
     *
     * @param string $url
     * @param array  $body
     *
     * @throws GuzzleException
     *
     * @return array
     */
    public function post(string $url, array $body)
    {
        $client = $this->client;

        $result = $client->post($url, [
            'headers'     => $this->getHeaders(),
            'form_params' => $body,
        ]);

        return NetworkResponse::format($result);
    }

    public function setBasicAuthorizationToken()
    {
        $this->headers['Authorization'] = 'Basic '.config('jenga.header');
    }

    public function setBearerAuthorizationToken()
    {
        $this->headers['Authorization'] = 'Bearer '.Authentication::getToken();
    }
}
