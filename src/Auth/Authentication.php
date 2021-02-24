<?php

namespace maggz69\JengaApi\Auth;


use maggz69\JengaApi\Exceptions\LoginDetailsIncorrect;
use maggz69\JengaApi\Network\NetworkPipeline;

class Authentication
{
    private const auth_endpoint = '/identity/v2/token';

    /**
     * Get the bearer token for authentication.
     *
     * Get the bearer token from jengahq api gateway using the login details provided
     * @return string
     * @throws LoginDetailsIncorrect
     */
    public static function getToken(): string
    {
        return self::tryLogin();
    }

    public static function tryLogin()
    {
        $token = null;

        $username = config('jenga.username');
        $password = config('jenga.password');

        $authData = [
            'username' => $username,
            'password' => $password
        ];


        $networkPipe = new NetworkPipeline();
        $networkPipe->setBasicAuthorizationToken();

        $result = $networkPipe->post(self::auth_endpoint, $authData);

        if (!$result['successful']) {
            throw new LoginDetailsIncorrect();
        }

        $token = $result['body']['access_token'];


        return $token;
    }
}