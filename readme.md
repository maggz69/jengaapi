## Jenga API Package for Laravel and PHP

[![StyleCI](https://github.styleci.io/repos/341956563/shield?branch=master)](https://github.styleci.io/repos/341956563?branch=master)
![GitHub last commit](https://img.shields.io/github/last-commit/maggz69/jengaapi?style=flat-square)

### Features Available

1. API
- [X] Account Services API
- [ ] Send Money API
- [ ] KYC API
2. Certificates
- [X] Generating Private and Public Key
- [X] Reading Private Key and Signing Requests
 
 ### Installation
 
 1. Install the package
 `composer require maggz69/jengaapi`
 
 2. Publish resources. This will publish a config file  `jenga.php`
 `php artisan vendor:publish`
 
 3. Either edit the `jenga.php` config file (config/jenga.php) / add the necessary environment variables (.env)
 
 These are 
 ```php
 // These variables are available in your jenga portal under the API Keys tab
 JENGA_USERNAME
 JENGA_PASSWORD
 JENGA_HEADER
 
 // This account number is under the account tab in your Jenga portal
 JENGA_ACCOUNT_ID
 ```
 
 4. You're now ready to go

### Certificates & Public Keys

In order to make use of jengaapi v2, some requests require for parameters to be signed. This package can sign those requests for you. 
To sign these requests, jenga needs a public key uploaded on their portal whereas you as the devleoper will use a private key to sign these requests.

If you'd like to use the package to create the public and private key for you, you can read the section on creating a public key below. You could also add your own private key 
path to the jenga config and change the local key to `false`

#### Creating A Public Key
 
 To create a certificate using the package, use the following artisan command
 
 `php artisan jenga:create-certificate`
 
 This will create both public and private keys and store them in the local storage (_laravelapp/storage/app/phpseclib_) as local_private_key.pem and local_public_key.pem

#### Upload Your Public Key

Upload the public key just generated to the jenga portal 

### Using the Package

Ideally there's two ways of making requests to the api. Either using all the classes under the _src/Api_ folder or directly through the `NetworkPipeline::class`

#### Api Classes

Simply make use of the relevant api class and pass the relevant data in the constructor. For any parameters that are optional, 
The package will either use the default data availavle in the config or use the default request data. Some examples of defaulted data are countryCode=KE, accountId 
which is retrieved from the config / env file.

A sample call is as such.

_Account Balance API_
```php

use maggz69\JengaApi\Api\AccountBalance;

//The returned value is an array. Any error in the network request (no internet, invalid authentication credentials e.t.c) will throw an appropriate exception

$result =  (new AccountBalance)->checkBalance();
$result2 = (new AccountBalance('KE',1234567))->checkBalance();

```

#### Network Pipeline

Inherently all the API Classes still make use of the NetworkPipeline class. To allow for flexibility, you can also directly call the Network pipeline class 
and make requests through the post / get function yourself. 

By default all requests don't have the signature / authentication attached to it. You will have to call these methods individually and as appropriate. 

For example 
_Account Balance Using Network Pipeline_

```php

use maggz69\JengaApi\Network\NetworkPipeline;

// instantiate the network pipeline and if there's a string meant to be added as a signature,
// add it here. If no signature is needed for the request, leave this parameter empty / null

$networkRequest = new NetworkPipeline('the_string_in_the_order_to_be_signed');

// if the bearer authorisation token is needed, call this function before the request
$networkRequest->setBearerAuthorizationToken();

// if the basic authorisation token is needed (such as for the login url), call this function instead
// $networkRequest->setBearerAuthorizationToken();
// we wont need it here thus I've left it commented out

// the body that will be added to the get request
$body = ['countryCode' => $this->countryCode, 'accountId' => $this->accountId];

// make the get request to the get function that takes the following parameters (url_to_be_accessed,body_to_be_sent_with_request)
$result = $networkRequest->get(Str::replaceArray('?', array_values($body), '/account/v2/accounts/balances/?/?'), $body);



```

### Authentication

By default using either the API classes or the NetworkPipeline will have the package automatically generate the Bearer token for you. 
You could also get the token yourself by making use of the Authentication class 

```php
namespace maggz69\JengaApi\Auth\Authentication;

$bearerToken = Authentication::getToken();
```

