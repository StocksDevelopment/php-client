<?php

namespace Stocks;

use Exception;
use Stocks\Loader\StocksExchangeAutoLoader;
use Stocks\Services\Service;

class StocksExchange
{

    const VERSION = "1.0.0";
    const ALL = 'ALL';
    const ORDER = 'ASC';
    const DEFAULT_COUNT = 50;

    private static $path;
    private static $library;
    private static $service;

    public $base_url = 'https://app.stocks.exchange/api2';
    public $api_key = null;
    public $api_secret = null;
    public $debug = null;


    /**
     * StocksExchange constructor.
     * @param null $api_key
     * @param null $api_secret
     * @param null $base_url
     * @param bool $debug
     * @throws \Exception
     */
    public function __construct($api_key = null, $api_secret = null, $base_url = null, $debug = false)
    {
        self::$path = (dirname(__FILE__));

//        StocksExchangeAutoLoader::init();

        self::$service = Service::init();

        if (!is_null($api_key)) {
            $this->api_key = $api_key;
        }
        if (!is_null($api_secret)) {
            $this->api_secret = $api_secret;
        }
        if (!is_null($base_url)) {
            $this->base_url = $base_url;
        }
        if (!is_null($debug)) {
            $this->debug = $debug;
        }

        self::$service->base_url = $this->base_url;
        self::$service->api_secret = $this->api_secret;
        self::$service->api_key = $this->api_key;
        self::$service->debug = $this->debug;
    }


    /**
     * @return StocksExchange
     * @throws \Exception
     */
    public static function init()
    {
        if (self::$library == null) {
//            require_once "loader" . DIRECTORY_SEPARATOR . "StocksExchangeAutoLoader.class.php";
            self::verifyDependencies();
            self::$library = new StocksExchange();
        }
        return self::$library;
    }

    /**
     * @return bool
     */
    private static function verifyDependencies()
    {
        $dependencies = true;
        try {
            if (!function_exists('curl_init')) {
                $dependencies = false;
                throw new Exception('StocksExchangeLibrary: cURL library is required.');
            }
            if (!class_exists('DOMDocument')) {
                $dependencies = false;
                throw new Exception('StocksExchangeLibrary: DOM XML extension is required.');
            }
        } catch (Exception $e) {
            return $dependencies;
        }
        return $dependencies;
    }

    /**
     * @return string
     */
    final public static function getVersion()
    {
        return self::VERSION;
    }

    /**
     * @return string
     */
    final public static function getPath()
    {
        return self::$path;
    }

    /**
     * PRIVATE API
     * Please using API Documentation http://help.stocks.exchange/api-integration/private-api
     */

    /**
     * @return string
     */
    final public function getInfo()
    {
        return self::$service->request('GetInfo');
    }

    /**
     * @param string $pair
     * @param null $from
     * @param int $count
     * @param null $from_id
     * @param null $end_id
     * @param string $order
     * @param null $since
     * @param null $end
     * @param string $type
     * @param string $owner
     * @return string
     */
    final public function getActiveOrders(
        $pair = null,
        $from = null,
        $count = null,
        $from_id = null,
        $end_id = null,
        $order = null,
        $since = null,
        $end = null,
        $type = null,
        $owner = null
    ) {
        if(is_null($pair))
            $pair = StocksExchange::ALL;
        if(is_null($count))
            $count = StocksExchange::DEFAULT_COUNT;
        if(is_null($order))
            $order = StocksExchange::ORDER;
        if(is_null($type))
            $type = StocksExchange::ALL;
        if(is_null($owner))
            $owner = StocksExchange::ALL;

        $params = array(
            'pair' => $pair,
            'count' => $count,
            'order' => $order,
            'type' => $type,
            'owner' => $owner
        );
        if (!is_null($from)) {
            $params['from'] = $from;
        }
        if (!is_null($from_id)) {
            $params['from_id'] = $from_id;
        }
        if (!is_null($end_id)) {
            $params['end_id'] = $end_id;
        }
        if (!is_null($since)) {
            $params['since'] = $since;
            $params['order'] = StocksExchange::ORDER;
        }
        if (!is_null($end)) {
            $params['end'] = $end;
            $params['order'] = StocksExchange::ORDER;
        }
        return self::$service->request('ActiveOrders', $params);
    }

    /**
     * @param string $type
     * @param string $pair
     * @param string $amount
     * @param string $rate
     * @return string
     */
    final public function setTrade($type, $pair, $amount, $rate)
    {
        $params = array(
            'type' => $type,
            'pair' => $pair,
            'amount' => $amount,
            'rate' => $rate
        );
        return self::$service->request('Trade', $params);
    }

    /**
     * @param int $order_id
     * @return string
     */
    final public function setCancelOrder($order_id)
    {
        $params = array(
            'order_id' => $order_id
        );
        return self::$service->request('CancelOrder', $params);
    }

    /**
     * @param string $pair
     * @param int $from
     * @param int $count
     * @param int $from_id
     * @param int $end_id
     * @param string $order
     * @param int $since
     * @param int $end
     * @param int $status
     * @param string $owner
     * @return string
     */
    final public function getTradeHistory(
        $pair = null,
        $from = null,
        $count = null,
        $from_id = null,
        $end_id = null,
        $order = null,
        $since = null,
        $end = null,
        $status = null,
        $owner = null
    ) {
        if(is_null($pair))
            $pair = StocksExchange::ALL;
        if(is_null($count))
            $count = StocksExchange::DEFAULT_COUNT;
        if(is_null($order))
            $order = StocksExchange::ORDER;
        if(is_null($status))
            $status = 3;
        if(is_null($owner))
            $owner = StocksExchange::ALL;

        $params = array(
            'pair' => $pair,
            'count' => $count,
            'order' => $order,
            'owner' => $owner,
            'status' => $status
        );
        if (!is_null($from)) {
            $params['from'] = $from;
        }
        if (!is_null($from_id)) {
            $params['from_id'] = $from_id;
        }
        if (!is_null($end_id)) {
            $params['end_id'] = $end_id;
        }
        if (!is_null($since)) {
            $params['since'] = $since;
            $params['order'] = StocksExchange::ORDER;
        }
        if (!is_null($end)) {
            $params['end'] = $end;
            $params['order'] = StocksExchange::ORDER;
        }
        return self::$service->request('TradeHistory', $params);
    }

    /**
     * @param string $currency
     * @param int $since
     * @param int $end
     * @return string
     */
    final public function getTradeRegisterHistory($currency = null, $since = null, $end = null)
    {
        if(is_null($currency))
            $currency = StocksExchange::ALL;

        $params = array(
            'currency' => $currency
        );
        if (!is_null($since)) {
            $params['since'] = $since;
        }
        if (!is_null($end)) {
            $params['end'] = $end;
        }
        return self::$service->request('TradeRegisterHistory', $params);
    }

    /**
     * @param int $since
     * @param int $end
     * @return string
     */
    final public function getUserHistory($since = null, $end = null)
    {
        $params = array();

        if (!is_null($since)) {
            $params['since'] = $since;
        }
        if (!is_null($end)) {
            $params['end'] = $end;
        }
        return self::$service->request('UserHistory', $params);
    }

    /**
     * @param string $currency
     * @param int $from
     * @param int $count
     * @param int $from_id
     * @param int $end_id
     * @param string $order
     * @param int $since
     * @param int $end
     * @param string $operation
     * @param string $status
     * @return string
     */
    final public function getTransHistory(
        $currency = null,
        $from = null,
        $count = null,
        $from_id = null,
        $end_id = null,
        $order = null,
        $since = null,
        $end = null,
        $operation = null,
        $status = null
    ) {
        if(is_null($currency))
            $currency = StocksExchange::ALL;
        if(is_null($count))
            $count = StocksExchange::DEFAULT_COUNT;
        if(is_null($order))
            $order = 'DESC';
        if(is_null($operation))
            $operation = StocksExchange::ALL;
        if(is_null($status))
            $status = 'FINISHED';

        $params = array(
            'currency' => $currency,
            'count' => $count,
            'order' => $order,
            'operation' => $operation,
            'status' => $status
        );
        if (!is_null($from)) {
            $params['from'] = $from;
        }
        if (!is_null($from_id)) {
            $params['from_id'] = $from_id;
        }
        if (!is_null($end_id)) {
            $params['end_id'] = $end_id;
        }
        if (!is_null($since)) {
            $params['since'] = $since;
            $params['order'] = StocksExchange::ORDER;
        }
        if (!is_null($end)) {
            $params['end'] = $end;
            $params['order'] = StocksExchange::ORDER;
        }
        if ($params['operation'] == StocksExchange::ALL) {
            $params['status'] = 'FINISHED';
        }
        return self::$service->request('TransHistory', $params);
    }


    /**
     * @param array $params
     * @return string
     */
    final public function getGrafic($params = array())
    {
        $data = array(
            'pair' => is_null($params['pair']) ? 'STEX_BTC' : $params['pair'],
            'count' => is_null($params['count']) ? StocksExchange::DEFAULT_COUNT : $params['count'],
            'order' => is_null($params['order']) ? 'DESC' : $params['order'],
            'interval' => is_null($params['interval']) ? '1D' : $params['interval'],
            'page' => is_null($params['page']) ? 1 : $params['page']
        );

        if (!is_null($params['since'])) {
            $data['since'] = $params['since'];
            $data['order'] = StocksExchange::ORDER;
        }

        if (!is_null($params['end'])) {
            $data['end'] = $params['end'];
            $data['order'] = StocksExchange::ORDER;
        }

        return self::$service->request('Grafic', $data);
    }

    /**
     * @param string $currency
     * @return string
     */
    final public function getGenerateWallets($currency)
    {
        $params = array('currency' => $currency);
        return self::$service->request('GenerateWallets', $params);
    }

    /**
     * @param string $currency
     * @return string
     */
    final public function getMakeDeposit($currency)
    {
        $params = array('currency' => $currency);
        return self::$service->request('Deposit', $params);
    }

    /**
     * @param string $currency
     * @param string $address
     * @param float $amount
     * @return string
     */
    final public function getMakeWithdraw($currency, $address, $amount)
    {
        $params = array(
            'currency' => $currency,
            'address' => $address,
            'amount' => $amount,
        );

        return self::$service->request('Withdraw', $params);
    }


    /**
     * @param $subject
     * @param int $ticket_category
     * @param $message
     * @param array $other
     * @return mixed|string
     */
    final public function setTicket($subject, $ticket_category = 5, $message, $other = [])
    {
        if(is_null($ticket_category))
            $ticket_category = 5;

        $params = array(
            'subject' => $subject,
            'ticket_category_id' => $ticket_category,
            'message' => $message
        );

        return self::$service->request('Ticket', array_merge($params,$other));
    }

    /**
     * @param int $ticket_id
     * @param int $ticket_category
     * @param int $ticket_status
     * @return string
     */
    final public function getTickets($ticket_id = null, $ticket_category = null, $ticket_status = null)
    {
        $params = array();

        if (!is_null($ticket_id)) {
            $params['ticket_id'] = $ticket_id;
        }
        if (!is_null($ticket_category)) {
            $params['ticket_category_id'] = $ticket_category;
        }
        if (!is_null($ticket_status)) {
            $params['ticket_status_id'] = $ticket_status;
        }

        return self::$service->request('GetTickets', $params);
    }

    /**
     * @param int $ticket_id
     * @param string $message
     * @return string
     */
    final public function setReplyTicket($ticket_id, $message = null)
    {
        if(is_null($message))
            $message = '';

        $params = array(
            'ticket_id' => $ticket_id,
            'message' => $message
        );
        return self::$service->request('ReplyTicket', $params);
    }

    /**
     * @param string $email
     * @return string
     */
    final public function setRemindPassword($email)
    {
        $params = array(
            'email' => $email
        );
        return self::$service->request('RemindPassword', $params);
    }

    /**
     * PUBLIC API
     * Please using API Documentation http://help.stocks.exchange/api-integration/public-api
     */

    /**
     * @return string
     */
    final public function getCurrencies()
    {
        return self::$service->request('GetCurrencies', null, false, false, '/currencies');
    }

    /**
     * @return string
     */
    final public function getMarkets()
    {
        return self::$service->request('GetMarkets', null, false, false, '/markets');
    }

    /**
     * @param string $currency1
     * @param string $currency2
     * @return string
     */
    final public function getMarketSummary($currency1 = null, $currency2 = null)
    {
        if(is_null($currency1))
            $currency1 = 'BTC';
        if(is_null($currency2))
            $currency2 = 'USDT';

        return self::$service->request('GetMarketSummary', null, false, false,
            '/market_summary/' . $currency1 . '/' . $currency2);
    }

    /**
     * @return string
     */
    final public function getTicker()
    {
        return self::$service->request('Ticker', null, false, false, '/ticker');
    }

    /**
     * @return string
     */
    final public function getPrices()
    {
        return self::$service->request('GetPrices', null, false, false, '/prices');
    }

    /**
     * @param string $pair
     * @return string
     */
    final public function getTradeHistoryPublic($pair = null)
    {
        if(is_null($pair))
            $pair = 'STEX_BTC';

        return self::$service->request('TradeHistoryPublic', null, false, false, '/trades?pair=' . $pair);
    }

    /**
     * @param string $pair
     * @return string
     */
    final public function getOrderBook($pair = null)
    {
        if(is_null($pair))
            $pair = 'STEX_BTC';

        return self::$service->request('OrderBook', null, false, false, '/orderbook?pair=' . $pair);
    }

    final public function getGraficPublic($params = array())
    {
        $data = array(
            'pair' => is_null($params['pair']) ? 'STEX_BTC' : $params['pair'],
            'count' => is_null($params['count']) ? StocksExchange::DEFAULT_COUNT : $params['count'],
            'order' => is_null($params['order']) ? 'DESC' : $params['order'],
            'interval' => is_null($params['interval']) ? '1D' : $params['interval'],
            'page' => is_null($params['page']) ? 1 : $params['page']
        );

        if (!is_null($params['since'])) {
            $data['since'] = $params['since'];
            $data['order'] = StocksExchange::ORDER;
        }

        if (!is_null($params['end'])) {
            $data['end'] = $params['end'];
            $data['order'] = StocksExchange::ORDER;
        }

        $get_data = http_build_query($data, '', '&');

        return self::$service->request('GraficPublic', null, false, false, '/grafic_public?' . $get_data);
    }
}