<?php

namespace maggz69\JengaApi\Api;

use Illuminate\Support\Str;
use maggz69\JengaApi\Interfaces\SignParametersInterface;
use maggz69\JengaApi\Network\NetworkPipeline;

class AccountBalance implements SignParametersInterface
{
    private const account_balance_endpoint = '/account/v2/accounts/balances/?/?';
    private string $countryCode;
    private string $accountId;

    /**
     * OpeningAndClosingBalance constructor.
     *
     * @param string|null $countryCode
     * @param string|null $accountId
     */
    public function __construct(string $countryCode = null, string $accountId = null)
    {
        $this->countryCode = isset($countryCode) ? $countryCode : config('jenga.account.country');
        $this->accountId = isset($accountId) ? $accountId : config('jenga.account.account_id');
    }

    /**
     * Check balance available in the account.
     *
     * @return array
     */
    public function checkBalance(): array
    {
        $networkRequest = new NetworkPipeline($this->getSingleParameterString());
        $networkRequest->setBearerAuthorizationToken();

        $body = ['countryCode' => $this->countryCode, 'accountId' => $this->accountId];

        $result = $networkRequest->get(Str::replaceArray('?', array_values($body), self::account_balance_endpoint), $body);

        return $result;
    }

    /**
     * Get a single string that contains the parameters that need to be signed.
     *
     * @return string
     */
    public function getSingleParameterString(): string
    {
        return $this->countryCode.$this->accountId;
    }
}
