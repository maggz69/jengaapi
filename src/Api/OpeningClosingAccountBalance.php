<?php


namespace maggz69\JengaApi\Api;


use maggz69\JengaApi\Interfaces\SignParametersInterface;
use maggz69\JengaApi\Network\NetworkPipeline;

class OpeningClosingAccountBalance implements SignParametersInterface
{

    private const account_opening_closing_balance_endpoint = '/account/v2/accounts/accountbalance/query';

    private string $countryCode;
    private string $accountId;
    private string $date;

    /**
     * OpeningClosingAccountBalance constructor.
     * @param string|null $countryCode
     * @param string|null $accountId
     * @param string $date
     */
    public function __construct(string $countryCode = null, string $accountId = null, string $date)
    {
        $this->countryCode = $countryCode ?? config('jenga.account.country');
        $this->accountId = $accountId ?? config('jenga.account.account_id');
        $this->date = $date;
    }

    public function getBalance()
    {
        $networkRequest = new NetworkPipeline($this->getSingleParameterString());
        $networkRequest->setBearerAuthorizationToken();

        $body = ['countryCode' => $this->countryCode, 'accountId' => $this->accountId, 'date' => $this->date];

        return $networkRequest->post(self::account_opening_closing_balance_endpoint, $body);
    }


    /**
     * Get a single string that contains the parameters that need to be signed.
     * @return string
     */
    public function getSingleParameterString(): string
    {
        return $this->accountId . $this->countryCode . $this->date;
    }
}