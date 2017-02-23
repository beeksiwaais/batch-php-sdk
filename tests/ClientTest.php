<?php


namespace Foodcheri\Batch\Test;

use Foodcheri\Batch\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testClientConstruct()
    {
        $apiKey = 'a';
        $restApiKey = 'b';
        $config = ['api_key' => $apiKey, 'rest_api_key' => $restApiKey];
        $client = new Client($config);

        self::assertArrayHasKey('api_key', $client->getConfig());
        self::assertArrayHasKey('rest_api_key', $client->getConfig());

        self::assertEquals($apiKey, $client->getConfig()['api_key']);
        self::assertEquals($restApiKey, $client->getConfig()['rest_api_key']);
    }

    /**
     * @expectedException Foodcheri\Batch\Exception\AuthentificationException
     */
    public function testClientWithoutConfiguration()
    {
        $config = ['api_key' => '', 'rest_api_key' => ''];
        $client = new Client($config);
    }


    public function testClientTransactionalApi()
    {
        $config = ['api_key' => 'a', 'rest_api_key' => 'b'];
        $client = new Client($config);

        self::assertInstanceOf("Foodcheri\\Batch\\Facade\\Transactional", $client->getApi('transactional'));
    }

    public function testClientCustomDataApi()
    {
        $config = ['api_key' => 'a', 'rest_api_key' => 'b'];
        $client = new Client($config);

        self::assertInstanceOf("Foodcheri\\Batch\\Facade\\CustomData", $client->getApi('custom_data'));
    }

    /**
     * @expectedException Foodcheri\Batch\Exception\UnknownEndpointException
     */
    public function testClientNotExistingApi()
    {
        $config = ['api_key' => 'a', 'rest_api_key' => 'b'];
        $client = new Client($config);

        $client->getApi('campaigns');
    }
}
