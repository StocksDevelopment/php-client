<?php


use PHPUnit\Framework\TestCase;
use Stocks\StocksExchange;


class BasicTest extends TestCase
{
    protected $object;
    protected $config;

    protected $key;
    protected $secret;

    protected $start_date;
    protected $end_date;

    protected function setUp() {
        $this->start_date = time();
        $this->end_date = $this->start_date - (24 * 60 * 60 * 2);
        $this->config = parse_ini_file("config.ini",true);

        $this->key = $this->config['auth']['key'];
        $this->secret = $this->config['auth']['secret'];

        $this->object = new StocksExchange($this->key, $this->secret, null, false);
    }

    /**
     *TEST Dependencies StocksExchange::class
     */
    public function testDependencies()
    {
        $this->assertInstanceOf(StocksExchange::class, $this->object);
    }

    /**
     *TEST PRIVATE METHOD API
     */
    public function testGetInfo()
    {
        $info = $this->object->getInfo();
        $this->assertTrue(is_int($info->success));
        $this->greaterThan(0);
        $this->assertNotEmpty($info->data);
    }


    /**
     *TEST PUBLIC METHOD API
     */
    public function testGetOrderBook()
    {
        $info = $this->object->getOrderBook();
        $this->assertTrue(is_int($info->success));
        $this->greaterThan(0);
        $this->assertNotEmpty($info->result->buy);
        $this->assertNotEmpty($info->result->sell);
    }

    /**
     *TEST VERSION
     */
    public function testGetVersion()
    {
        $version = $this->object->getVersion();
        $this->assertInternalType('string', $version);
        $this->assertTrue($version == StocksExchange::VERSION);
    }


}