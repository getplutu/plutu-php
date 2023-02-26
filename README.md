<p align="center">
  <a href="https://plutu.ly" target="_blank">
    <img src="https://plutu.ly/wp-content/uploads/2022/03/plutu-logo.svg" alt="Plutu" width="140" height="84">
  </a>
</p>

# Official Plutu SDK for PHP

[![Latest Stable Version](https://poser.pugx.org/plutu/plutu-php/v/stable.svg)](https://packagist.org/packages/plutu/plutu-php)
[![Version](http://poser.pugx.org/plutu/plutu-php/version)](https://packagist.org/packages/plutu/plutu-php)
[![Total Downloads](http://poser.pugx.org/plutu/plutu-php/downloads)](https://packagist.org/packages/plutu/plutu-php)
[![License](https://poser.pugx.org/plutu/plutu-php/license)](https://packagist.org/packages/plutu/plutu-php)

The Plutu PHP package provides a streamlined integration of Plutu's services into PHP projects. It offers a generic interface that enables easy interaction with the Plutu API and services.

## Getting started

### Requirments

Before you can use the Plutu PHP package, you need to have a Plutu API key, access token, and secret key. You can obtain these from your [Plutu](https://plutu.ly) account dashboard.

- PHP version 8.0 or higher

### Official Documentation

Documentation for Plutu API can be found on the [Plutu Docs](https://docs.plutu.ly) website.

### Installation

You can install the Plutu PHP package via Composer by running the following command:

```
composer require plutu/plutu-php
```

### Usage

To use the Plutu PHP package in your project, you first need to include the Composer autoload file:


```php
require_once __DIR__ . '/vendor/autoload.php';

```
To set credentials for Plutu PHP, you can use the following method:

```php
use Plutu\Services\PlutuAdfali;
use Plutu\Services\PlutuSadad;
use Plutu\Services\PlutuLocalBankCards;
use Plutu\Services\PlutuTlync;

// Adfali service
$api = new PlutuAdfali;
$api->setCredentials('api_key', 'access_token');
// Sadad service
$api = new PlutuSadad;
$api->setCredentials('api_key', 'access_token');
// Local Bank Cards service
$api = new PlutuLocalBankCards;
$api->setCredentials('api_key', 'access_token', 'secret_key');
// Tlync service
$api = new PlutuTlync;
$api->setCredentials('api_key', 'access_token', 'secret_key');
```

You can then use the various methods provided by the Plutu PHP to interact with the Plutu API. Each Plutu service has different methods and arguments, which you can check out in the examples provided ```examples``` section below.


```php
$mobileNumber = '090000000'; // Mobile number should start with 09
$amount = 5.0; // amount in float format

try {

    $api = new PlutuAdfali;
    $api->setCredentials('api_key', 'access_token');
    $apiResponse = $api->verify($mobileNumber, $amount);

    if ($apiResponse->getOriginalResponse()->isSuccessful()) {
        // Process ID should be sent in the confirmation step
        $processId = $apiResponse->getProcessId();
    } elseif ($apiResponse->getOriginalResponse()->hasError()) {
        $errorCode = $apiResponse->getOriginalResponse()->getErrorCode();
        $errorMessage = $apiResponse->getOriginalResponse()->getErrorMessage();
    }

// Handle exceptions that may be thrown during the execution of the code
} catch (\Exception $e) {
    $exception = $e->getMessage();
}
```

## Examples

The Plutu PHP package includes several examples that demonstrate how to use the package to interact with the Plutu API. The examples cover various use cases for Plutu's services. You can find the examples in the [Examples](https://github.com/getplutu/plutu-php/blob/main/examples.md) document.

Each example includes code snippets with explanations, as well as a link to the full source code. We recommend reviewing the examples to get a better understanding of how to use the package in your own projects.

##### List of Examples

- [Usage](https://github.com/getplutu/plutu-php/blob/main/examples.md#usage)
- [Initialization](https://github.com/getplutu/plutu-php/blob/main/examples.md#initialization)
- [Services](https://github.com/getplutu/plutu-php/blob/main/examples.md#services)
    - [Adfali Payment Service](https://github.com/getplutu/plutu-php/blob/main/examples.md#adfali-payment-service)
        - [Verify Process (Send OTP)](https://github.com/getplutu/plutu-php/blob/main/examples.md#verify-process-send-otp)
        - [Confirm Process (Pay)](https://github.com/getplutu/plutu-php/blob/main/examples.md#confirm-process-pay)
    - [Sadad Payment Service](https://github.com/getplutu/plutu-php/blob/main/examples.md#sadad-payment-service)
        - [Verify Process (Send OTP)](https://github.com/getplutu/plutu-php/blob/main/examples.md#verify-process-send-otp-1)
        - [Confirm Process (Pay)](https://github.com/getplutu/plutu-php/blob/main/examples.md#confirm-process-pay-1)
    - [Local Bank Cards Payment Service](https://github.com/getplutu/plutu-php/blob/main/examples.md#local-bank-cards-payment-service)
        - [Confirm (Pay)](https://github.com/getplutu/plutu-php/blob/main/examples.md#confirm-pay)
        - [Callback Handler](https://github.com/getplutu/plutu-php/blob/main/examples.md#Callback-handler)
    - [T-Lync Payment Service](https://github.com/getplutu/plutu-php/blob/main/examples.md#t-lync-payment-service)
        - [Confirm (Pay)](https://github.com/getplutu/plutu-php/blob/main/examples.md#confirm-pay-1)
        - [Callback Handler](https://github.com/getplutu/plutu-php/blob/main/examples.md#callback-handler-1)
        - [Return Handler](https://github.com/getplutu/plutu-php/blob/main/examples.md#return-handler)
- [Exceptions and Error Handling](https://github.com/getplutu/plutu-php/blob/main/examples.md#exceptions-and-error-handling)

## Official integrations

The following integrations are fully supported and maintained by the Plutu team.

- [Plutu Laravel](https://github.com/getplutu/plutu-laravel)

## Resources

- [Plutu Docs](https://docs.plutu.ly)

## License

The Plutu PHP package is open-source software licensed under the [MIT](https://opensource.org/licenses/MIT) License.
