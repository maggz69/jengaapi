<?php

namespace maggz69\JengaApi\Exceptions;

class LoginDetailsIncorrect extends \Exception
{
    /**
     * LoginDetailsIncorrect errorMessage.
     */
    public function errorMessage(): string
    {
        return 'The details you provided could not be used to generate an access token. Kindly check that the keys, username and password used are valid';
    }
}
