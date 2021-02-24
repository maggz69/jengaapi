<?php


namespace maggz69\JengaApi\Exceptions;


class MissingPrivateKey extends \Exception
{
    /**
     * Missing Private Key errorMessage.
     */
    public function errorMessage(): string
    {
        return "The key file you provided at the following path ".$this->getMessage()." could not be read. Kindly confirm that a file exists at that path";
    }
}