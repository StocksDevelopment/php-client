<?php

namespace Stocks;

use Exception;
use Stocks\Services\Service;
use RuntimeException;

/**
 * Class StocksExchange
 * @package StocksExchange
 */
class StocksExchange
{
    public static $path;
    public static $library;
    public static $service;

    public $base_url;

    /**
     * @return StocksExchange
     * @throws \Exception
     */
    public static function init()
    {
        if (self::$library == null) {
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
                throw new RuntimeException('StocksExchange: cURL library is required.');
            }
            if (!class_exists('DOMDocument')) {
                $dependencies = false;
                throw new RuntimeException('StocksExchange: DOM XML extension is required.');
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
        $composer_version = json_decode(file_get_contents('./../composer.json'));
        return $composer_version->version;
    }

    /**
     * @return string
     */
    final public static function getPath()
    {
        return self::$path;
    }
}