<?php

use GuzzleHttp\Client;

class Service
{
    /**
     * @var
     */
    private static $resources;

    public $base_url;
    public $api_key;
    public $api_secret;
    public $debug;

    const ENCRYPT_ALGORITHM = 'sha512';
    const SLEEP_SECOND = 1;

    private function __construct()
    {

    }


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

    public function request($method, $params = array() , $post = true, $sign = true, $url = '/')
    {
        sleep(Service::SLEEP_SECOND);

        $client = new Client();

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
                    'Sign' => $sign
                ),
                'body' => $params
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
            $response = $request->getBody()->getContents();
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
    private function buildQuery($params)
    {
        return http_build_query($params, '', '&');
    }

}
