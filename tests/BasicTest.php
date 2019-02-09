<?php


use PHPUnit\Framework\TestCase;
use Stocks\ApiVersion\Two;


class BasicTest extends TestCase
{
    protected $object;

    protected $key;
    protected $secret;

    protected $start_date;
    protected $end_date;

    protected function setUp() {
        $this->start_date = time();
        $this->end_date = $this->start_date - (24 * 60 * 60 * 2);

        $this->key = getenv("key");
        $this->secret = getenv("secret");

        try {
            $this->object = new Two($this->key, $this->secret, null, false);
        } catch (Exception $e) {
            echo $e;
        }
    }

    /**
     *TEST Dependencies StocksExchange::class
     */
    public function testDependencies()
    {
        $this->assertInstanceOf(Two::class, $this->object);
    }

    /**
     *TEST VERSION
     */
    public function testGetVersion()
    {
        $version = $this->object->getVersion();
        $composer_version = json_decode(file_get_contents('./../composer.json'));
        $this->assertInternalType('string', $version);
        $this->assertTrue($version == $composer_version->version);
    }


}