# STEX ( former Stocks.Exchange) (PHP API client)
STEX ( former Stocks.Exchange) provides all the core exchange functionality, and additional merchant tools available via the HTTP API where all returned messages are in JSON. It's much easier to work with the API by using one of the clients provided by Stocks.Exchange (now available only in PHP), so while this page describes the API in case you want or need to build your own client, the examples use the PHP client.
## Requirements
- PHP >= 5.4.27
## Dependent Libraries
- "guzzlehttp/guzzle": "^6.3@dev",
- "tunaabutbul/cloudflare-middleware": "v1.2"

## General
The base URL for all the requests other than public methods is 
```php
https://app.stex.com/api2
https://api3.stex.com
```

## Getting started
- [Documentation API V2](http://help.stex.com/api-integration).
- [Sandbox API V3](https://apidocs.stex.com).

To get started with the PHP client, here's a snippet for creating a client with existing credentials:
> In order to use the API functions, you must have an API key and API secret, which is generated in the user profile.

## Usage
If you want to use it as is, you just have to require the composer autoload file to instatiate Stocks.Exchange objects as shown in the following example.
Go to the folder where the library is located and run terminal command in console

```
composer require stocks_exchange/php-client
```
After install use for example this code!

### Example API V2
```php
<?php
// import the StocksExchange Class
use Stocks\ApiVersion\Two;
// include composer autoload
require dirname(__FILE__).'/vendor/autoload.php';

$key = '1234567890'; // API key sample
$secret = '1234567890'; // API secret sample
// create an StocksExchange instance with API key, API secret, URL and DEBUG
$stocks = new Two($key, $secret, 'https://app.stocks.exchange/api2', false);
// Private method getInfo()
$stocks->getInfo();

```

```
=> stdClass Object
   (
       [success] => 1
       [data] => stdClass Object
           (
               [email] => test@gmail.com
               [username] => test@gmail.com
               [userSessions] => Array()
   
               [funds] => stdClass Object
                   (
                       [WOKE] => *
                       [LYF] => *
                       [STCN] => *
                       [BTC] => *
                   )
   
               [hold_funds] => stdClass Object
                   (
                       [WOKE] => *
                       [LYF] => *
                       [STCN] => *
                       [BTC] => *
                   )
   
               [wallets_addresses] => stdClass Object
                   (
                       [WOKE] => 
                       [LYF] => ****
                       [STCN] => 
                       [BTC] => 
                   )
   
               [publick_key] => stdClass Object
                   (
                       [WOKE] => 
                       [LYF] => 
                       [STCN] => 
                       [BTC] => 
                   )
   
               [Assets portfolio] => stdClass Object
                   (
                       [portfolio_price] => *
                       [frozen_portfolio_price] => *
                       [count] => *
                       [assets] => Array()
   
                   )
   
               [open_orders] => *
               [server_time] => 1523697767
           )
   
   )
```
## Lists Methods
```
getInfo() // Get information about your account.
getActiveOrders() // Get information about active orders.
setTrade() // Create orders for the purchase and sale.            
setCancelOrder() // Cancel selected order.
getTradeHistory() // Get information about all closed orders.
getTradeRegisterHistory() // Get information about all closed orders from Register
getUserHistory() // Get information about all orders User 
getTransHistory() // Get information about your deposits and withdrawals.
getGrafic() // Get information about trade statistic.
getGenerateWallets() // Generate currency wallet address.
getMakeDeposit() // Get information about your wallet to deposit funds.
getMakeWithdraw() // Withdraw your funds.
setTicket() // Create ticket.
getTickets() // Get tickets.
setReplyTicket() // Reply ticket.
setRemindPassword() // Restore password
getCurrencies() // Get all available currencies with additional info.
getMarkets() // Get all available currency pairs with additional info.
getMarketSummary() // Get currency pair with additional info.
getTicker() // Use it to get the recommended retail exchange rates for all currency pairs.
getPrices() // Use it to get the new retail exchange rates for all currency pairs.
getTradeHistoryPublic() // Used to retrieve the latest trades that have occurred for a specific market. 
getOrderBook() // Used to get retrieve the orderbook for a given market.
getGraficPublic() // Get information about trade statistic
```

### Example V3
```php
<?php
use Stocks\ApiVersion\Three;
$se = new Three(
    1, // Client ID
    'ssss', // // Client Secret
    [
        'tokenObject' => [
            'access_token' => '',
            'refresh_token' => ''
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
            'access_token' => '',
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
