# Plutu PHP Examples

This document contains examples of how to use the Plutu PHP package to integrate payment services into your PHP application.

## Getting started

- [Usage](#usage)
- [Initialization](#initialization)
- [Services](#services)
    - [Adfali Payment Service](#adfali-payment-service)
        - [Verify Process (Send OTP)](#verify-process-send-otp)
        - [Confirm Process (Pay)](#confirm-process-pay)
    - [Sadad Payment Service](#sadad-payment-service)
        - [Verify Process (Send OTP)](#verify-process-send-otp-1)
        - [Confirm Process (Pay)](#confirm-process-pay-1)
    - [Local Bank Cards Payment Service](#local-bank-cards-payment-service)
        - [Confirm (Pay)](#confirm-pay)
        - [Callback Handler](#Callback-handler)
    - [T-Lync Payment Service](#t-lync-payment-service)
        - [Confirm (Pay)](#confirm-pay-1)
        - [Callback Handler](#callback-handler-1)
        - [Return Handler](#return-handler)
- [Exceptions and Error Handling](#exceptions-and-error-handling)

### Usage

To use the Plutu PHP package in your project, you first need to include the Composer autoload file:


```php
require_once __DIR__ . '/vendor/autoload.php';

```

### Initialization

Plutu PHP package provides several payment services, including `PlutuAdfali`, `PlutuSadad`, `PlutuLocalBankCards`, and `PlutuTlync`. To use a payment service, include the corresponding service class in your PHP file using the use statement:


```php
use Plutu\Services\PlutuAdfali;
use Plutu\Services\PlutuSadad;
use Plutu\Services\PlutuLocalBankCards;
use Plutu\Services\PlutuTlync;

```

To set credentials for Plutu PHP, you can use the following method:

```php
use Plutu\Services\PlutuAdfali;
$api = new PlutuAdfali;
$api->setCredentials('api_key', 'access_token', 'secret_key');
```

Alternatively, you can set each credential separately using the following methods:

```php
$api->setApiKey('api_key');
$api->setAccessToken('access_token');
$api->setSecretKey('secret_key');
```

## Services

To utilize Plutu's payment services, including `PlutuAdfali`, `PlutuSadad`, `PlutuLocalBankCards`, and `PlutuTlync`, please refer to the following sections for detailed PHP examples and explanations. These examples will assist you in integrating the services seamlessly into your applications

---

### Adfali Payment Service

#### Verify Process (Send OTP)

To initiate a payment process with Adfali, you need to send an OTP to the customer's mobile number. You can use the verify method of the PlutuAdfali class to do this.

| Parameter       | Type   | Description                                             |
|-----------------|--------|---------------------------------------------------------|
| `$mobileNumber` | string | The customer's mobile number in the format 09XXXXXXXX.  |
| `$amount`       | float  | The amount of the transaction.                          |

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

        // Possible errors from Plutu API
        // @see https://docs.plutu.ly/api-documentation/errors Plutu API Error Documentation
        $errorCode = $apiResponse->getOriginalResponse()->getErrorCode();
        $errorMessage = $apiResponse->getOriginalResponse()->getErrorMessage();
        $statusCode = $apiResponse->getOriginalResponse()->getStatusCode();
        $responseData = $apiResponse->getOriginalResponse()->getBody();

    }

// Handle exceptions that may be thrown during the execution of the code
// The following are the expected exceptions that may be thrown:
// Check the "Handle Exceptions and Errors" section for more details
// 
// InvalidAccessTokenException, InvalidApiKeyException
// InvalidMobileNumberException, InvalidAmountException
} catch (\Exception $e) {
    $exception = $e->getMessage();
}

```

#### Confirm Process (Pay)

Once the customer receives the OTP, they need to enter it to confirm the payment process. You can use the confirm method of the PlutuAdfali class to confirm the payment.

| Parameter    | Type   | Description                                             |
|--------------|--------|---------------------------------------------------------|
| `$processId` | string | Process ID received from the verification step.         |
| `$code`      | string | OTP number received by the customer during verification, must be 4 digits.|
| `$amount`    | float  | The amount of the transaction.                          |
| `$invoiceNo` | string | Invoice or order number associated with the transaction, containing only alphanumeric characters, periods, dashes, and underscores..|

```php
$processId = 'xxxxx'; // the Process ID that received in the verification step
$code = '1111'; // OTP
$amount = 5.0; // amount in float format
$invoiceNo = 'inv-12345'; // invoice number

try {

    $api = new PlutuAdfali;
    $api->setCredentials('api_key', 'access_token');

    $apiResponse = $api->confirm($processId, $code, $amount, $invoiceNo);

    if($apiResponse->getOriginalResponse()->isSuccessful()){

        // The transaction has been completed
        // Plutu Transaction ID
        $transactionId = $apiResponse->getTransactionId();
        // Response Data
        $data = $apiResponse->getOriginalResponse()->getBody();

    } elseif($apiResponse->getOriginalResponse()->hasError()) {

        // Possible errors from Plutu API
        // @see https://docs.plutu.ly/api-documentation/errors Plutu API Error Documentation
        $errorCode = $apiResponse->getOriginalResponse()->getErrorCode();
        $errorMessage = $apiResponse->getOriginalResponse()->getErrorMessage();
        $statusCode = $apiResponse->getOriginalResponse()->getStatusCode();
        $responseData = $apiResponse->getOriginalResponse()->getBody();

    }

// Handle exceptions that may be thrown during the execution of the code
// The following are the expected exceptions that may be thrown:
// Check the "Handle Exceptions and Errors" section for more details
// 
// InvalidAccessTokenException, InvalidApiKeyException
// InvalidProcessIdException, InvalidCodeException, InvalidAmountException, InvalidInvoiceNoException
} catch (\Exception $e) {
    $exception = $e->getMessage();
}

```

---

### Sadad Payment Service

#### Verify Process (Send OTP)

To initiate a payment process with Sadad, you need to send an OTP to the customer's mobile number. You can use the verify method of the PlutuSadad class to do this.

| Parameter        | Type   | Description                                                                           |
|------------------|--------|---------------------------------------------------------------------------------------|
| `$mobileNumber`  | string | The customer's mobile number subscribed in Sadad service, in the format 091XXXXXXX.   |
| `$birthYear`     | int    | The customer's birth year related to the mobile number.                               |
| `$amount`        | float  | The amount of the transaction.                                                        |

```php
$mobileNumber = '090000000'; // Mobile number should start with 09
$birthYear = '1991'; // Birth year
$amount = 5.0; // amount in float format

try {

    $api = new PlutuSadad;
    $api->setCredentials('api_key', 'access_token');
    $apiResponse = $api->verify($mobileNumber, $birthYear, $amount);

    if ($apiResponse->getOriginalResponse()->isSuccessful()) {

        // Process ID should be sent in the confirmation step
        $processId = $apiResponse->getProcessId();

    } elseif ($apiResponse->getOriginalResponse()->hasError()) {

        // Possible errors from Plutu API
        // @see https://docs.plutu.ly/api-documentation/errors Plutu API Error Documentation
        $errorCode = $apiResponse->getOriginalResponse()->getErrorCode();
        $errorMessage = $apiResponse->getOriginalResponse()->getErrorMessage();
        $statusCode = $apiResponse->getOriginalResponse()->getStatusCode();
        $responseData = $apiResponse->getOriginalResponse()->getBody();

    }

// Handle exceptions that may be thrown during the execution of the code
// The following are the expected exceptions that may be thrown:
// Check the "Handle Exceptions and Errors" section for more details
// 
// InvalidAccessTokenException, InvalidApiKeyException
// InvalidMobileNumberException, InvalidBirthYearException, InvalidAmountException
} catch (\Exception $e) {
    $exception = $e->getMessage();
}

```

#### Confirm Process (Pay)

Once the customer receives the OTP, they need to enter it to confirm the payment process. You can use the confirm method of the PlutuSadad class to confirm the payment.

| Parameter    | Type   | Description                                             |
|--------------|--------|---------------------------------------------------------|
| `$processId` | string | Process ID received from the verification step.         |
| `$code`      | string | OTP number received by the customer during verification, must be 6 digits.|
| `$amount`    | float  | The amount of the transaction.                          |
| `$invoiceNo` | string | Invoice or order number associated with the transaction, containing only alphanumeric characters, periods, dashes, and underscores..|

```php
$processId = 'xxxxx'; // the Process ID that received in the verification step
$code = '111111'; // OTP
$amount = 5.0; // amount in float format
$invoiceNo = 'inv-12345'; // invoice number
    
try {

    $api = new PlutuSadad;
    $api->setCredentials('api_key', 'access_token');
    $apiResponse = $api->confirm($processId, $code, $amount, $invoiceNo);

    if($apiResponse->getOriginalResponse()->isSuccessful()){

        // The transaction has been completed
        // Plutu Transaction ID
        $transactionId = $apiResponse->getTransactionId();
        // Response Data
        $data = $apiResponse->getOriginalResponse()->getBody();

    } elseif($apiResponse->getOriginalResponse()->hasError()) {

        // Possible errors from Plutu API
        // @see https://docs.plutu.ly/api-documentation/errors Plutu API Error Documentation
        $errorCode = $apiResponse->getOriginalResponse()->getErrorCode();
        $errorMessage = $apiResponse->getOriginalResponse()->getErrorMessage();
        $statusCode = $apiResponse->getOriginalResponse()->getStatusCode();
        $responseData = $apiResponse->getOriginalResponse()->getBody();

    }

// Handle exceptions that may be thrown during the execution of the code
// The following are the expected exceptions that may be thrown:
// Check the "Handle Exceptions and Errors" section for more details
// 
// InvalidAccessTokenException, InvalidApiKeyException
// InvalidProcessIdException, InvalidCodeException, InvalidAmountException, InvalidInvoiceNoException
} catch (\Exception $e) {
    $exception = $e->getMessage();
}

```

---

### Local Bank Cards Payment Service

The Local Bank Cards Service provides a way to initiate payment processes with Local Bank Cards.

#### Confirm (Pay)

You can use the confirm method of the PlutuLocalBankCards class to initiate this payment process.

| Parameter    | Type   | Description                                                                              |
|--------------|--------|------------------------------------------------------------------------------------------|
| `$amount`    | float  | The amount of the transaction.                                                           |
| `$invoiceNo` | string | Invoice or order number associated with the transaction, containing only alphanumeric characters, periods, dashes, and underscores..                                 |
| `$returnUrl` | string | The callback URL that the transaction will redirect to after completion or cancellation. |


```php
$amount = 5.0; // amount in float format
$invoiceNo = 'inv-12345'; // invoice number
$returnUrl = 'https://example.com/callback/handler'; // the url to handle the callback from plutu

try {

    $api = new PlutuLocalBankCards;
    $api->setCredentials('api_key', 'access_token', 'secret_key');
    $apiResponse = $api->confirm($amount, $invoiceNo, $returnUrl);

    if ($apiResponse->getOriginalResponse()->isSuccessful()) {

        // Redirect URL for Plutu checkout page
        $redirectUrl = $apiResponse->getRedirectUrl();

        // You should rediect the customer to payment checkout page
        // header("location: " . $redirectUrl);

    } elseif ($apiResponse->getOriginalResponse()->hasError()) {

        // Possible errors from Plutu API
        // @see https://docs.plutu.ly/api-documentation/errors Plutu API Error Documentation
        $errorCode = $apiResponse->getOriginalResponse()->getErrorCode();
        $errorMessage = $apiResponse->getOriginalResponse()->getErrorMessage();
        $statusCode = $apiResponse->getOriginalResponse()->getStatusCode();
        $responseData = $apiResponse->getOriginalResponse()->getBody();

    }

// Handle exceptions that may be thrown during the execution of the code
// The following are the expected exceptions that may be thrown:
// Check the "Handle Exceptions and Errors" section for more details
// 
// InvalidAccessTokenException, InvalidApiKeyException, InvalidSecretKeyException,
// InvalidAmountException, InvalidInvoiceNoException, InvalidReturnUrlException
} catch (\Exception $e) {
    $exception = $e->getMessage();
}

```


#### Callback Handler

Once the payment is completed or cancelled on Plutu, the user will be redirected to the ```return_url``` that you provided during the confirm step. This redirect will result in a GET request, and the returned data can be handled using the ```$_GET``` superglobal variable. It is important to handle this callback data to update the order or invoice payment status in your system.


```php
$parameters = $_GET;

try {
    
    $api = new PlutuLocalBankCards;
    $api->setSecretKey('secret_key');
    // OR
    //$api->setCredentials(null, null, 'secret_key');
    $callback = $api->callbackHandler($parameters);

    // Get all parameters
    // @see https://docs.plutu.ly/api-documentation/payments/local-bank-cards#callback-handler
    $callbackParameters = $callback->getParameters();

    // The transaction has been completed and approved
    if($callback->isApprovedTransaction()){
        // Get transaction ID
        $transactionId = $callback->getParameter('transaction_id');
    }

    // The end-user canceled the payment page
    if($callback->isCanceledTransaction()){
        // Canceled tranasction
    }

// Handle exceptions that may be thrown during the execution of the code
// The following are the expected exceptions that may be thrown:
// Check the "Handle Exceptions and Errors" section for more details
// 
// InvalidSecretKeyException,
// InvalidCallbackHashException
} catch (\Exception $e) {
    $exception = $e->getMessage();
}
```

---

### T-Lync Payment Service

T-Lync Service provides a way to initiate payment processes with multiple payment gateways such as Tadawul Cards, MobiCash, Adfali, Sadad, etc.

You can use the confirm method of the PlutuTlync class to initiate this payment process.

#### Confirm (Pay)

| Parameter      | Type   | Description                                                                                |
|----------------|--------|--------------------------------------------------------------------------------------------|
| `$amount`      | float  | The amount of the transaction.                                                             |
| `$invoiceNo`   | string | Invoice or order number associated with the transaction, containing only alphanumeric characters, periods, dashes, and underscores..                                   |
| `$returnUrl`   | string | The return URL that the transaction will redirect to after completion.                     |
| `$callbackUrl` | string | The callback URL that will receive a `POST` request when the transaction is completed.     |


```php
$amount = 5.0; // amount in float format
$invoiceNo = 'inv-12345'; // invoice number
$returnUrl = 'https://example.com/return/handler'; // the url to handle the return from plutu after payment completed from T-Lync
$callbackUrl = 'https://example.com/callback/handler'; // the url to handle the callback trgigger from plutu after payment completed from T-Lync

try {

    $api = new PlutuTlync;
    $api->setCredentials('api_key', 'access_token', 'secret_key');
    $apiResponse = $api->confirm($amount, $invoiceNo, $returnUrl, $callbackUrl);

    if ($apiResponse->getOriginalResponse()->isSuccessful()) {

        // Redirect URL for Plutu and T-Lync checkout page
        $redirectUrl = $apiResponse->getRedirectUrl();

        // You should rediect the customer to payment checkout page
        // header("location: " . $redirectUrl);

    } elseif ($apiResponse->getOriginalResponse()->hasError()) {

        // Possible errors from Plutu API
        // @see https://docs.plutu.ly/api-documentation/errors Plutu API Error Documentation
        $errorCode = $apiResponse->getOriginalResponse()->getErrorCode();
        $errorMessage = $apiResponse->getOriginalResponse()->getErrorMessage();
        $statusCode = $apiResponse->getOriginalResponse()->getStatusCode();
        $responseData = $apiResponse->getOriginalResponse()->getBody();

    }

// Handle exceptions that may be thrown during the execution of the code
// The following are the expected exceptions that may be thrown:
// Check the "Handle Exceptions and Errors" section for more details
// 
// InvalidAccessTokenException, InvalidApiKeyException, InvalidSecretKeyException,
// InvalidAmountException, InvalidInvoiceNoException, InvalidReturnUrlException, InvalidCallbackUrlException
} catch (\Exception $e) {
    $exception = $e->getMessage();
}

```

#### Callback Handler

Once the payment is successfully completed on T-Lync, Plutu will trigger a callback to the ```callback_url``` that you provided during the confirmation step. The callback will be sent as a ```POST``` request to that URL. It is important to handle this callback data to update the order or invoice payment status in your system.

```php
$data = file_get_contents("php://input");
$parameters = (array) json_decode($data, true);

try {
    
    $api = new PlutuTlync;
    $api->setSecretKey('secret_key');
    // OR
    //$api->setCredentials(null, null, 'secret_key');
    $callback = $api->callbackHandler($parameters);

    // The transaction has been completed and approved
    if($callback->isApprovedTransaction()){
        // Get transaction ID
        $transactionId = $callback->getParameter('transaction_id');
        // Get payment method that end-user use it in T-Lync service to pay the invoice
        $transactionId = $callback->getParameter('paymet_method');
        // Get the invoice number to update the payment status in your system
        $invoiceNo = $callback->getParameter('invoice_no');
    }
    
    // Get all parameters
    // @see https://docs.plutu.ly/api-documentation/payments/t-lync#callback-handler
    $callbackParameters = $callback->getParameters();

// Handle exceptions that may be thrown during the execution of the code
// The following are the expected exceptions that may be thrown:
// Check the "Handle Exceptions and Errors" section for more details
// 
// InvalidSecretKeyException,
// InvalidCallbackHashException
} catch (\Exception $e) {
    $exception = $e->getMessage();
}
```

#### Return Handler

If the end-user clicks on the 'Back to Website' button after completing the payment on T-Lync checkout page, they will be redirected to the ```return_url``` that you provided during the confirm step. This redirect will result in a ```GET``` request, and the returned data can be accessed using the ```$_GET``` superglobal variable. It is important to handle this data appropriately in your code to ensure a seamless user experience.


```php
$parameters = $_GET;

try {
    
    $api = new PlutuTlync;
    $api->setSecretKey('secret_key');
    // OR
    //$api->setCredentials(null, null, 'secret_key');
    $callback = $api->returnHandler($parameters);

    // The transaction has been completed and approved
    if($callback->isApprovedTransaction()){
        // Retrieve the invoice number to redirect the end-user to the corresponding invoice page on your system.
        $invoiceNo = $callback->getParameter('invoice_no');
    }

    // Get all parameters
    // @see https://docs.plutu.ly/api-documentation/payments/t-lync#return-handler
    $callbackParameters = $callback->getParameters();

// Handle exceptions that may be thrown during the execution of the code
// The following are the expected exceptions that may be thrown:
// Check the "Handle Exceptions and Errors" section for more details
// 
// InvalidSecretKeyException,
// InvalidCallbackHashException
} catch (\Exception $e) {
    $exception = $e->getMessage();
}
```

---

## Exceptions and Error Handling

These exceptions are thrown by the Plutu PHP Package before sending requests via the API, which helps to reduce potential errors. It is important to catch and handle these exceptions appropriately, provide clear and meaningful error messages to end-users, and handle the exceptions that need to be handled in your code. 

The following are the expected exceptions that may be thrown:


```php
Plutu\Services\Exceptions\InvalidAccessTokenException
Plutu\Services\Exceptions\InvalidApiKeyException
Plutu\Services\Exceptions\InvalidSecretKeyException
Plutu\Services\Exceptions\InvalidMobileNumberException
Plutu\Services\Exceptions\InvalidBirthYearException
Plutu\Services\Exceptions\InvalidAmountException
Plutu\Services\Exceptions\InvalidProcessIdException
Plutu\Services\Exceptions\InvalidCodeException
Plutu\Services\Exceptions\InvalidInvoiceNoException
Plutu\Services\Exceptions\InvalidCallbackHashException
Plutu\Services\Exceptions\InvalidReturnUrlException
Plutu\Services\Exceptions\InvalidCallbackUrlException

```

Here are the detailed descriptions for each exception that may occur while using the Plutu service.

| Exception                         | Description                                                                                                                        |
|-----------------------------------|------------------------------------------------------------------------------------------------------------------------------------|
| `InvalidAccessTokenException`     | This exception is thrown when the Access Token is missing and not configured.                                                      |
| `InvalidApiKeyException`          | This exception is thrown when the API Key is missing and not configured.                                                           |
| `InvalidSecretKeyException`       | This exception is thrown when the Secret Key is missing and not configured.                                                        |
| `InvalidMobileNumberException`    | This could occur if the mobile number format is incorrect, Sadad number must be a valid number starting with `091` and the other services that require a mobile number must be a valid number starting with `09`. |
| `InvalidBirthYearException`       | This could occur if the year provided is not a valid year or not valid format and ensure it is in the correct format and within the minimum age limit.                                          |
| `InvalidCodeException`            | If the One-Time Password (OTP) provided is incorrect. For Sadad service, the OTP must be a six-digit number, and for Adfali service, it must be a four-digit number.                              |
| `InvalidAmountException`          | This could occur if the amount provided is negative, zero, or empty.                                                               |
| `InvalidProcessIdException`       | This could occur if the process ID does not send in confirmation step.                                                             |
| `InvalidInvoiceNoException`       | This could occur if the invoice number is not in a valid format.                                                                   |
| `InvalidCallbackHashException`    | This exception is thrown when an invalid callback hash is provided to the Plutu service. This could occur if the hash is incorrect or if it has been tampered with. To resolve this issue, you must check your account `secret key`. |
| `InvalidReturnUrlException`       | This could occur if the return URL is not in a valid format.                                                                       |
| `InvalidCallbackUrlException`     | This could occur if the callback URL is not in a valid format.                                                                     |
