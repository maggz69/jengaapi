<?php

namespace maggz69\JengaApi\Network;

use Psr\Http\Message\ResponseInterface;

/**
 * Class NetworkResponse.
 */
class NetworkResponse
{
    /**
     * NetworkResponse constructor.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    public function __construct(ResponseInterface $response)
    {
        return self::format($response);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    public static function format(ResponseInterface $response): array
    {
        $res = [];

        $res['successful'] = ($response->getStatusCode() === 200 || $response->getStatusCode() === 201);
        $res['response_code'] = $response->getStatusCode();
        $res['body'] = json_decode((string) $response->getBody(), true);

        return $res;
    }
}
