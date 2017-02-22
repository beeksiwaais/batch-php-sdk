<?php


namespace Foodcheri\Batch\Test\Endpoint;


use Foodcheri\Batch\Endpoint\Transactional;
use PHPUnit\Framework\TestCase;

class TransactionalTest extends TestCase
{
    public function testCustomDataUrlAndHeaderFormatting()
    {
        $api_key = 'a';
        $rest_api_key = 'b';

        $url = "https://api.batch.com/1.1/$api_key/transactional/send";
        $method = 'POST';

        $transactional = new Transactional();
        $transactional->buildUrl(['api_key' => $api_key, 'rest_api_key' => $rest_api_key]);

        $this->assertEquals($url, $transactional->getRequest()->getRequestInfo('url'));
        $this->assertEquals($method, $transactional->getRequest()->getRequestInfo('method'));

        $this->assertContains('X-Authorization: ' . $rest_api_key, $transactional->getRequest()->getRequestInfo('headers'));
    }

    public function testTransactionalBodyFormatting()
    {
        $params = [
            'group_id' => 65,
            'recipients' => ['custom_ids' => [867, 32397]],
            'message' => [
                'title' => 'Hello',
                'body' => 'Just a test'
            ]
        ];

        $transactional = new Transactional();
        $transactional->buildParams($params);

        $decodedJson = json_decode($transactional->getRequest()->getRequestInfo('options')[CURLOPT_POSTFIELDS], true);

        foreach ($params as $key => $value) {
            $this->assertEquals($value, $decodedJson[$key]);
        }

        $this->assertContains('Content-type: application/json', $transactional->getRequest()->getRequestInfo('headers'));
    }
}
