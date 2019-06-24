<?php

namespace Stocks\ApiVersion;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use Stocks\Services\Service;
use Stocks\StocksExchange;
use Tuna\CloudflareMiddleware;


class Three extends StocksExchange {

    public $option;
    public $client_id;
    public $client_secret;
    public $debug;
    public $currentToken;

    public $base_url = 'https://api3.stex.com';
    const JSON_SETTINGS = 'settings.json';

    /**
     * Three constructor.
     * @param $client_id
     * @param $client_secret
     * @param $option
     * @param null $base_url
     * @param bool $debug
     */
    public function __construct($client_id, $client_secret, $option, $base_url = null, $debug = false)
    {
        self::$path = (dirname(__FILE__));

        self::$service = Service::init();

        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->option = $option;

        if (!is_null($base_url)) {
            $this->base_url = $base_url;
        }

        if (!is_null($debug)) {
            $this->debug = $debug;
        }
    }

    /**
     * @return mixed|string
     */
    final public function profileInfo()
    {
        $url = $this->base_url.'/profile/info';
        return $this->request([], $url);
    }

    /**
     * @param $params
     * @return mixed|string
     */
    final public function wallets($params)
    {
        $url = $this->base_url.'/profile/wallets';
        return $this->request($params, $url);
    }

    /**
     * @param $walletId
     * @return mixed|string
     */
    final public function walletsById($walletId)
    {
        $url = $this->base_url."/profile/wallets/$walletId";
        return $this->request([], $url);
    }

    /**
     * @param $currencyId
     * @return mixed|string
     */
    final public function addWalletsByCurrencyId($currencyId)
    {
        $url = $this->base_url."/profile/wallets/$currencyId";
        return $this->request([], $url, 'post');
    }

    /**
     * @param $walletId
     * @return mixed|string
     */
    final public function getWalletsAddress($walletId)
    {
        $url = $this->base_url."/profile/wallets/address/$walletId";
        return $this->request([], $url);
    }

    /**
     * @param $walletId
     * @return mixed|string
     */
    final public function newWalletsAddress($walletId)
    {
        $url = $this->base_url."/profile/wallets/address/$walletId";
        return $this->request([], $url, 'post');
    }

    /**
     * @param $params
     * @return mixed|string
     */
    final public function deposits($params)
    {
        $url = $this->base_url."/profile/deposits";
        return $this->request($params, $url);
    }

    /**
     * @param $id
     * @return mixed|string
     */
    final public function depositsById($id)
    {
        $url = $this->base_url."/profile/deposits/$id";
        return $this->request([], $url);
    }

    /**
     * @param $params
     * @return mixed|string
     */
    final public function withdrawals($params)
    {
        $url = $this->base_url."/profile/withdrawals";
        return $this->request($params, $url);
    }

    /**
     * @param $id
     * @return mixed|string
     */
    final public function withdrawalsById($id)
    {
        $url = $this->base_url."/profile/withdrawals/$id";
        return $this->request([], $url);
    }

    /**
     * @param int $currency_id
     * @param $amount
     * @param string $address
     * @param string $additional_address
     * @return mixed|string
     */
    final public function addWithdrawal($currency_id, $amount, $address, $additional_address)
    {
        $url = $this->base_url."/profile/withdraw";
        $params = [
            "currency_id" => $currency_id,
            "amount" => $amount,
            "address" => $address
        ];
        if ($additional_address) {
            $params['additional_address_parameter'] = $additional_address;
        }
        return $this->request($params, $url, 'post', 'form');
    }

    /**
     * @param $id
     * @return mixed|string
     */
    final public function cancelWithdrawalById($id)
    {
        $url = $this->base_url."/profile/withdraw/$id";
        return $this->request([], $url, 'delete');
    }

    /**
     * @param $params
     * @return mixed|string
     */
    final public function reportsOrders($params)
    {
        $url = $this->base_url."/reports/orders";
        return $this->request($params, $url);
    }

    /**
     * @param $orderId
     * @return mixed|string
     */
    final public function reportsOrdersById($orderId)
    {
        $url = $this->base_url."/reports/orders/$orderId";
        return $this->request([], $url);
    }

    /**
     * @return mixed|string
     */
    final public function allTradingOrders()
    {
        $url = $this->base_url."/trading/orders";
        return $this->request([], $url);
    }

    /**
     * @return mixed|string
     */
    final public function deleteAllTradingOrders()
    {
        $url = $this->base_url."/trading/orders";
        return $this->request([], $url, 'delete');
    }

    /**
     * @param $currencyPairId
     * @return mixed|string
     */
    final public function tradingOrdersByPair($currencyPairId)
    {
        $url = $this->base_url."/trading/orders/$currencyPairId";
        return $this->request([], $url);
    }

    /**
     * @param $currencyPairId
     * @return mixed|string
     */
    final public function deleteTradingOrdersByPair($currencyPairId)
    {
        $url = $this->base_url."/trading/orders/$currencyPairId";
        return $this->request([], $url, 'delete');
    }

    /**
     * @param $currencyPairId
     * @param $type
     * @param $amount
     * @param $price
     * @return mixed|string
     */
    final public function addTradingOrdersByPair($currencyPairId, $type, $amount, $price)
    {
        $url = $this->base_url."/trading/orders/$currencyPairId";
        $params = [
            "type" => $type,
            "amount" => $amount,
            "price" => $price
        ];
        return $this->request($params, $url, 'post', 'form');
    }

    /**
     * @param $orderId
     * @return mixed|string
     */
    final public function tradingOrderById($orderId)
    {
        $url = $this->base_url."/trading/order/$orderId";
        return $this->request([], $url);
    }

    /**
     * @param $orderId
     * @return mixed|string
     */
    final public function deleteTradingOrderById($orderId)
    {
        $url = $this->base_url."/trading/order/$orderId";
        return $this->request([], $url, 'delete');
    }

    /**
     * @return mixed|string
     */
    final public function publicCurrencies()
    {
        $url = $this->base_url."/public/currencies";
        return $this->request([], $url, 'get', 'url', false);
    }

    /**
     * @param $currencyId
     * @return mixed|string
     */
    final public function publicCurrenciesById($currencyId)
    {
        $url = $this->base_url."/public/currencies/$currencyId";
        return $this->request([], $url, 'get', 'url', false);
    }

    /**
     * @return mixed|string
     */
    final public function publicMarkets()
    {
        $url = $this->base_url."/public/markets";
        return $this->request([], $url, 'get', 'url', false);
    }


    /**
     * @param $code
     * @return mixed|string
     */
    final public function publicCurrencyPairsList($code)
    {
        $url = $this->base_url."/public/currency_pairs/list/$code";
        return $this->request([], $url, 'get', 'url', false);
    }

    /**
     * @param $currencyPairId
     * @return mixed|string
     */
    final public function publicCurrencyPairsById($currencyPairId)
    {
        $url = $this->base_url."/public/currency_pairs/$currencyPairId";
        return $this->request([], $url, 'get', 'url', false);
    }

    /**
     * @return mixed|string
     */
    final public function publicTicker()
    {
        $url = $this->base_url."/public/ticker";
        return $this->request([], $url, 'get', 'url', false);
    }

    /**
     * @param $currencyPairId
     * @return mixed|string
     */
    final public function publicTickerById($currencyPairId)
    {
        $url = $this->base_url."/public/ticker/$currencyPairId";
        return $this->request([], $url, 'get', 'url', false);
    }

    /**
     * @param $currencyPairId
     * @param $params
     * @return mixed|string
     */
    final public function publicTrades($currencyPairId, $params)
    {
        $url = $this->base_url."/public/trades/$currencyPairId";
        return $this->request($params, $url, 'get', 'url', false);
    }

    /**
     * @param $currencyPairId
     * @param $params
     * @return mixed|string
     */
    final public function publicOrderBook($currencyPairId, $params)
    {
        $url = $this->base_url."/public/orderbook/$currencyPairId";
        return $this->request($params, $url, 'get', 'url', false);
    }

    /**
     * @param $currencyPairId
     * @param $timeStart
     * @param $timeEnd
     * @param array $params
     * @param string $candlesType
     * @return mixed|string
     */
    final public function publicChart($currencyPairId, $timeStart, $timeEnd, $params = [], $candlesType = '1')
    {
        $url = $this->base_url."/public/chart/$currencyPairId/$candlesType";
        $params['timeStart'] = $timeStart;
        $params['timeEnd'] = $timeEnd;
        return $this->request($params, $url, 'get', 'url', false);
    }

    /**
     * @return mixed|string
     */
    final public function publicPing()
    {
        $url = $this->base_url."/public/ping";
        return $this->request([], $url, 'get', 'url', false);
    }


    /**
     * @param $params
     * @param $url
     * @param string $method
     * @param string $type
     * @param bool $auth
     * @return mixed|string
     */
    public function request($params, $url, $method = 'get', $type = 'url', $auth = true)
    {
        sleep(Service::SLEEP_SECOND);
        $client = new Client([
            'cookies' => new FileCookieJar('cookies.txt')
        ]);
        $client->getConfig('handler')->push(CloudflareMiddleware::create());
        $params = is_null($params) ? array() : $params;
        $post_data = self::$service->buildQuery($params);
        $url = $url.($post_data == '' ? '' : '?'.$post_data);
        $options = [
            'debug' => $this->debug,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'stocks.exchange-client',
            ]
        ];

        if ($auth) {
            $options['headers']['Authorization'] = 'Bearer ' . $this->getToken($client);
        }

        if ($type == 'form') $options['form_params'] = $params;
        try {
            $request = $client->$method(
                $url,
                $options
            );
            $response = json_decode($request->getBody());
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        return $response;
    }

    private function getToken($client)
    {
        try {
            if (file_exists(self::JSON_SETTINGS)) {
                $this->currentToken = json_decode(file_get_contents(self::JSON_SETTINGS));
            } else {
                $this->currentToken = json_decode(json_encode([
                    'access_token' => $this->option['tokenObject']['access_token'],
                    'refresh_token' => $this->option['tokenObject']['refresh_token'],
                    'expires_in' => null,
                    'expires_in_date' => null
                ]));
            }
            if ($this->currentToken && $this->currentToken->expires_in_date && date($this->currentToken->expires_in_date) > date("Y-m-d H:i:s",
                    time())) {
                return $this->currentToken->access_token;
            }
            $request = $client->post($this->option['accessTokenUrl'], [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->currentToken->refresh_token,
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'scope' => $this->option['scope'],
                ],
            ]);
            if ($request->getStatusCode() == 200) {
                $this->currentToken = json_decode($request->getBody());
                $this->currentToken->expires_in_date = date("Y-m-d H:i:s", time() + $this->currentToken->expires_in);
                file_put_contents(self::JSON_SETTINGS, json_encode($this->currentToken));
            } else {
                $this->destroySettings();
            }
        } catch (\Exception $e) {
            $this->destroySettings();
            throw new \Error($e->getMessage());
        }
        return $this->currentToken->access_token;
    }

    private function destroySettings()
    {
        if (file_exists(self::JSON_SETTINGS)) {
            unlink(self::JSON_SETTINGS);
        }
    }
}