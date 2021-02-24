<?php

namespace maggz69\JengaApi\Api;

use maggz69\JengaApi\Interfaces\SignParametersInterface;
use maggz69\JengaApi\Network\NetworkPipeline;

class AccountFullStatement implements SignParametersInterface
{
    private const account_mini_statement_endpoint = '/account/v2/accounts/fullstatement';

    private string $countryCode;
    private string $accountId;
    private array $dates = [];
    private int $limit = 20;
    private ?array $extraAttributes;

    /**
     * Account full statement constructor.
     *
     * @param string|null $countryCode
     * @param string|null $accountId
     * @param array       $dates
     * @param null        $limit
     * @param array|null  $extraAttributes
     */
    public function __construct(string $countryCode = null, string $accountId = null, array $dates, $limit = null, array $extraAttributes = null)
    {
        $this->countryCode = isset($countryCode) ? $countryCode : config('jenga.account.country');
        $this->accountId = isset($accountId) ? $accountId : config('jenga.account.account_id');

        if (is_array($dates)) {
            $this->dates = $dates;
        } else {
            $this->dates = (array) $dates;
        }

        $this->limit = $limit ?? $this->limit;

        $this->extraAttributes = $extraAttributes;
    }

    public function getFullStatement()
    {
        $networkRequest = new NetworkPipeline($this->getSingleParameterString());
        $networkRequest->setBearerAuthorizationToken();

        $body = ['accountNumber' => $this->accountId, 'fromDate' => $this->dates[0], 'toDate' => $this->dates[1], 'limit' => $this->limit];

        if (isset($this->extraAttributes)) {
            $body = array_merge($body, $this->extraAttributes);
        }

        $result = $networkRequest->post(self::account_mini_statement_endpoint, $body);

        return $result;
    }

    /**
     * Get a single string that contains the parameters that need to be signed.
     *
     * @return string
     */
    public function getSingleParameterString(): string
    {
        return $this->accountId.$this->countryCode.$this->dates[1];
    }
}
