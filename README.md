# Stocks.exchange HTTP API client
## API client for PHP

## General
StocksExchange provides all the core exchange functionality, and additional merchant tools available via the HTTP API where all returned messages are in JSON. It's much easier to work with the API by using one of the clients provided by StocksExchange (now available only in PHP), so while this page describes the API in case you want or need to build your own client, the examples use the PHP client.

The base URL for all the requests other than ticker is https://stocks.exchange/api2.
> In order to use the API functions, you must have an API key and API secret, which is generated in the user profile.

## Authentication
Most of the calls described next require authentication. All those are POST requests that must be created according to the following steps:
  1.    Include a unique 'nonce' argument (numeric value).
  2.    Include an 'method' argument set to the end point for the request.
  3.    Represent the (key, value) pairs present in the request as JSON. Let SIGNDATA be the result of this step.
  4.    Calculate the HMAC-SHA512 of SIGNDATA using your API secret.
  5.    Create a 'Sign' header and set its value to the hexadecimal digest resulting from the HMAC-SHA512 calculation.
  6.    Create a 'Key' header and set its value to your API key.
  7.    Send a POST request with the parameter SIGNDATA.
>For all purposes the nonce must be unique within an API key. If this is not the case, or the signature doesn't match, an error will be returned.

To get started with the PHP client, here's a snippet for creating a client with existing credentials:
>require_once(__DIR__ .'/stockExchange.php');
$API = array('key' => <<API key>>,  'secret' => <<API secret>>);

$client = new StockExange($API['key'], $API['secret']);
The $client instance is assumed to be available in the following examples.
Note that the clients provided do all the steps required for signing data. The limited testing can be done through the testi_api page.
## Errors
When you handling an API request all answers start by parameter "status".
If any errors occur when handling an API request, the response will follow this format:
{
  "status": 0,
  "error": <<error message describing problem goes here>>
}
>Any response that contains the 'error' field should be considered unsuccessful for the reason given. Note that HTTP errors may also be thrown depending on the issue.

There are two types of API errors when you handling an API request:
  1.   	Common errors (main errors such as “Invalid Sign” etc).
  2.   	Method errors (errors that generate if something going wrong when you processing a particular API request). These errors will be described individually in each method.

## Common Errors
### Here is a list with common errors and their descriptions:
  1.    Invalid Key - not generated key or the key does not correspond to the a user
  2.    Invalid Sign - bad hash-code
  3.    Invalid Nonce - wrong or empty parameter Nonce
  4.    Duplicate Request - parameter Nonce is not unique
  5.    Invalid Method - this method is wrong or missing  	

## For detailed information, please, visit our [Documentation](http://help.stocks.exchange/api-integration).
