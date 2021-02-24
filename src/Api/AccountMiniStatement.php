<?php


namespace maggz69\JengaApi\Api;


use Illuminate\Support\Str;
use maggz69\JengaApi\Interfaces\SignParametersInterface;
use maggz69\JengaApi\Network\NetworkPipeline;

class AccountMiniStatement implements SignParametersInterface
{

    private const account_mini_statement_endpoint = '/account/v2/accounts/balances/?/?';
    private string $countryCode;
    private string $accountId;

    /**
     * AccountMiniStatement constructor.
     * @param string|null $countryCode
     * @param string|null $accountId
     */
    public function __construct(string $countryCode = null, string $accountId = null)
    {
        $this->countryCode = isset($countryCode) ? $countryCode : config('jenga.account.country');
        $this->accountId = isset ($accountId) ? $accountId : config('jenga.account.account_id');
    }


    public function getMiniStatement()
    {
        $networkRequest = new NetworkPipeline($this->getSingleParameterString());
        $networkRequest->setBearerAuthorizationToken();

        $result = $networkRequest->get(Str::replaceArray('?', [$this->countryCode, $this->accountId], self::account_mini_statement_endpoint));

        return $result;
    }


    /**
     * Get a single string that contains the parameters that need to be signed.
     * @return string
     */
    public function getSingleParameterString(): string
    {
        return $this->countryCode . $this->accountId;
    }
}