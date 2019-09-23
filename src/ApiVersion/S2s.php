<?php

namespace Stocks\ApiVersion;

use Error;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use Stocks\Services\Service;
use Tuna\CloudflareMiddleware;


class S2s extends Three {

    public $s2s;

    /**
     * S2s constructor.
     * @param $option
     * @param  bool  $s2s
     * @param  null  $base_url
     * @param  bool  $debug
     */
    public function __construct($option, $s2s = true, $base_url = null, $debug = false)
    {
        self::$path = (dirname(__FILE__));

        self::$service = Service::init();

        $this->option = $option;

        if (!is_null($s2s)) {
            $this->s2s = $s2s;
        }

        if (!is_null($base_url)) {
            $this->base_url = $base_url;
        }

        if (!is_null($debug)) {
            $this->debug = $debug;
        }
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
        } catch (Exception $e) {
            $response = $e->getMessage();
        }

        return $response;
    }

    private function getToken($client)
    {
        try {
            $this->currentToken = json_decode(json_encode([
                'access_token' => $this->option['tokenObject']['access_token'],
            ]));
        } catch (Exception $e) {
            throw new Error($e->getMessage());
        }
        return $this->currentToken->access_token;
    }
}