<?php

namespace Stocks\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use Tuna\CloudflareMiddleware;

class Service
{
    private static $resources;

    public $base_url;

    // Params API V2
    public $api_key;
    public $api_secret;
    public $debug;

    const ENCRYPT_ALGORITHM = 'sha512';
    const SLEEP_SECOND = 1;

    // Params API V3
    public $option;
    public $client_id;
    public $client_secret;

    /**
     * @return Service
     */
    public static function init()
    {
        if (self::$resources == null) {
            self::$resources = new Service();
        }
        return self::$resources;
    }


    /**
     * @param string $method
     * @param array $params
     * @param bool $post
     * @param bool $sign
     * @param string $url
     * @return mixed|string
     */
    public function request($method, $params = array() , $post = true, $sign = true, $url = '/')
    {
        sleep(Service::SLEEP_SECOND);

        $client = new Client([
            'cookies' => new FileCookieJar('cookies.txt')
        ]);
        $client->getConfig('handler')->push(CloudflareMiddleware::create());

        $params = is_null($params) ? array() : $params;

        if ($sign) {
            $params['nonce'] = $this->gen_nonce();
            $params['method'] = $method;
            $post_data = $this->buildQuery($params);
            $sign = hash_hmac(Service::ENCRYPT_ALGORITHM, $post_data, $this->api_secret);
            $options = array(
                'debug' => $this->debug,
                'headers' => array(
                    'Key' => $this->api_key,
                    'Sign' => $sign,
                ),
                'form_params' => $params,
            );
        }else{
            $options = array(
                'debug' => $this->debug
            );
        }

        try {
            if($post){
                $request = $client->post(
                    $this->base_url.$url,
                    $options
                );
            }else{
                $request = $client->get(
                    $this->base_url.$url,
                    $options
                );
            }
            $response = json_decode($request->getBody());
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        return $response;
    }

    /**
     * @return int
     */
    private function gen_nonce()
    {
        return time();
    }

    /**
     * @param $params
     * @return string
     */
    public function buildQuery($params)
    {
        return http_build_query($params, '', '&');
    }

}