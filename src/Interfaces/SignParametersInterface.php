<?php

namespace maggz69\JengaApi\Interfaces;

/**
 * Ensure the parent class signs requests that are necessary.
 */
interface SignParametersInterface
{
    /**
     * Get a single string that contains the parameters that need to be signed.
     *
     * @return string
     */
    public function getSingleParameterString(): string;
}
