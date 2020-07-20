# STEX ( former Stocks.Exchange) (PHP API client)
STEX ( former Stocks.Exchange) provides all the core exchange functionality, and additional merchant tools available via the HTTP API where all returned messages are in JSON. It's much easier to work with the API by using one of the clients provided by stex.com (now available only in PHP), so while this page describes the API in case you want or need to build your own client, the examples use the PHP client.
## Requirements
- PHP >= 5.4.27
## Dependent Libraries
- "guzzlehttp/guzzle": "^6.3@dev",
- "tunaabutbul/cloudflare-middleware": "v1.2"

## General
The base URL for all the requests other than public methods is 
```php
https://api3.stex.com
```

## Getting started
- [Sandbox API V3](https://apidocs.stex.com).

To get started with the PHP client, here's a snippet for creating a client with existing credentials:
> In order to use the API functions, you must have an API key and API secret, which is generated in the user profile.

## Usage
If you want to use it as is, you just have to require the composer autoload file to instatiate stex.com objects as shown in the following example.
Go to the folder where the library is located and run terminal command in console

```
composer require stocks_exchange/php-client
```
After install use for example this code!

### Example
```php
<?php

use Stocks\ApiVersion\Three;
require dirname(__FILE__).'/vendor/autoload.php';

$se = new Three(
    'client_id', // Client ID
    'client_secret', // Client Secret
    [
        'tokenObject' => [
            'access_token' => null,
            'refresh_token' => null
        ],
        'accessTokenUrl' => 'https://api3.stex.com/oauth/token',
        'scope' => 'trade profile reports withdrawal'
    ],
    null, // URL
    false // Debug
);

print_r($se->publicPing());
```
## Lists Methods
- [Sandbox API V3](https://apidocs.stex.com).

```
profileInfo() // Get general information about the current user.
wallets() // Get a list of user wallets.
walletsById() // Single wallet information            
addWalletsByCurrencyId() // Create a wallet for given currency
getWalletsAddress() // Get deposit address for given wallet
newWalletsAddress() // Create new deposit address
deposits() // Get a list of deposits made by user
depositsById() // Get deposit by id
withdrawals() // Get a list of withdrawals made by user
withdrawalsById() // Get withdrawal by id
addWithdrawal() // Create withdrawal request
cancelWithdrawalById() // Cancel unconfirmed withdrawal
reportsOrders() // Get past orders
reportsOrdersById() // Get specified order details
allTradingOrders() // List your currently open orders
deleteAllTradingOrders() // Delete all active orders 
tradingOrdersByPair() // List your currently open orders for given currency pair 
deleteTradingOrdersByPair() // Delete active orders for given currency pair
addTradingOrdersByPair() // Create new order and put it to the orders processing queue
tradingOrderById() // Get a single order
deleteTradingOrderById() // Cancel order                   
                         
publicCurrencies() // Available Currencies
publicCurrenciesById() // Get currency info   
publicMarkets() // Available markets 
publicCurrencyPairsList() // Available currency pairs
publicCurrencyPairsById() // Get currency pair information 
publicTicker() // Tickers list for all currency pairs
publicTickerById() // Ticker for currency pair
publicTrades() // Trades for given currency pair  
publicOrderBook() // Orderbook for given currency pair
publicChart() // A list of candles for given currency pair
publicPing() // Test API is working and get server time
```

### Example V3 Server-To-Server integrations
```php
<?php
use Stocks\ApiVersion\S2s;
$se = new S2s(
    [
        'tokenObject' => [
            'access_token' => '<access_token>',
        ],
        'accessTokenUrl' => 'https://api3.stex.com/oauth/token',
        'scope' => 'profile trade withdrawal reports push settings'
    ]
);
print_r($se->publicPing());
```

## Common Errors
### Here is a list with common errors and their descriptions:
  1.    Invalid Key - not generated key or the key does not correspond to the a user
  2.    Invalid Sign - bad hash-code
  3.    Invalid Nonce - wrong or empty parameter Nonce
  4.    Duplicate Request - parameter Nonce is not unique
  5.    Invalid Method - this method is wrong or missing  	
