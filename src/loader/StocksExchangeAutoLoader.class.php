<?php

class StocksExchangeAutoLoader
{
    public static $loader;

    private static $dirs = array(
        'services'
    );

    /**
     * StocksExchangeAutoLoader constructor.
     */
    private function __construct()
    {
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }
        spl_autoload_register(array($this, 'addClass'));
    }

    /**
     * @return StocksExchangeAutoLoader
     * @throws Exception
     */
    public static function init()
    {
        if (!function_exists('spl_autoload_register')) {
            throw new Exception("StocksExchangeLibrary: Standard PHP Library (SPL) is required.");
        }
        if (self::$loader == null) {
            self::$loader = new StocksExchangeAutoLoader();
        }
        return self::$loader;
    }

    /**
     * @param $class
     */
    private function addClass($class)
    {
        foreach (self::$dirs as $key => $dir) {
            $file = StocksExchange::getPath() . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $class . '.class.php';
            if (file_exists($file) && is_file($file)) {
                require_once $file;
            }
        }
    }
}