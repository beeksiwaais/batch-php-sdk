<?php

namespace Foodcheri\Batch\Test\Endpoint;


use Foodcheri\Batch\Endpoint\PostCustomData;
use PHPUnit\Framework\TestCase;

class PostCustomDataTest extends TestCase
{

    public function testIsAssocArray()
    {
        $validArray = ["a" => "A", "b" => "B", "c" => "C"];
        $errorArray = [1, 2, 3, 4];

        $postCustomData = new PostCustomData();

        $this->assertTrue($postCustomData->isAssocArray($validArray));
        $this->assertFalse($postCustomData->isAssocArray($errorArray));

    }

    public function testCustomDataUrlAndHeaderSingleFormatting()
    {
        $user_id = 1;
        $api_key = 'a';
        $rest_api_key = 'b';

        $url = "https://api.batch.com/1.0/$api_key/data/users/$user_id";
        $method = 'POST';

        $postCustomData = new PostCustomData();
        $postCustomData->buildUrl(['user_id' => $user_id, 'api_key' => $api_key, 'rest_api_key' => $rest_api_key]);

        $this->assertEquals($url, $postCustomData->getRequest()->getRequestInfo('url'));
        $this->assertEquals($method, $postCustomData->getRequest()->getRequestInfo('method'));

        $this->assertContains('X-Authorization: ' . $rest_api_key, $postCustomData->getRequest()->getRequestInfo('headers'));
    }

    public function testCustomDataUrlAndHeaderBatchFormatting()
    {
        $api_key = 'a';
        $rest_api_key = 'b';

        $url = "https://api.batch.com/1.0/$api_key/data/users";
        $method = 'POST';

        $postCustomData = new PostCustomData();
        $postCustomData->buildUrl(['api_key' => $api_key, 'rest_api_key' => $rest_api_key]);

        $this->assertEquals($url, $postCustomData->getRequest()->getRequestInfo('url'));
        $this->assertEquals($method, $postCustomData->getRequest()->getRequestInfo('method'));

        $this->assertContains('X-Authorization: ' . $rest_api_key, $postCustomData->getRequest()->getRequestInfo('headers'));
    }

    public function testCustomDataBodySingleFormatting()
    {
        $now = new \DateTime('now');
        $data = [
            'now' => $now,
            'cities' => ['Tokyo', 'Paris'],
            "color" => 'blue'
        ];

        $expectedList = [
            'date(u.now)' => $now->getTimestamp(),
            'ut.cities' => ['Tokyo', 'Paris'],
            'u.color' => 'blue'
        ];

        $postCustomData = new PostCustomData();
        $postCustomData->buildParams($data);

        $decodedJson = json_decode($postCustomData->getRequest()->getRequestInfo('options')[CURLOPT_POSTFIELDS], true);

        $this->assertTrue($decodedJson['overwrite']);
        foreach ($expectedList as $expectedKey => $expectedValue) {
            $this->assertEquals($expectedValue, $decodedJson['values'][$expectedKey]);
        }

        $this->assertContains('Content-type: application/json', $postCustomData->getRequest()->getRequestInfo('headers'));
    }

    public function testCustomDataBodyBatchFormatting()
    {
        $now = new \DateTime('now');
        $id = 867;
        $data = [
            $id => [
                'now' => $now,
                'cities' => ['Tokyo', 'Paris'],
                "color" => 'blue'
            ]
        ];

        $expectedList = [
            'date(u.now)' => $now->getTimestamp(),
            'ut.cities' => ['Tokyo', 'Paris'],
            'u.color' => 'blue'
        ];

        $postCustomData = new PostCustomData();
        $postCustomData->buildParams($data);

        $decodedJson = json_decode($postCustomData->getRequest()->getRequestInfo('options')[CURLOPT_POSTFIELDS], true);

        $this->assertTrue($decodedJson['overwrite']);
        $this->assertEquals($decodedJson['values']['id'], $id);
        foreach ($expectedList as $expectedKey => $expectedValue) {
            $this->assertEquals($expectedValue, $decodedJson['values']['update'][$expectedKey]);
        }

        $this->assertContains('Content-type: application/json', $postCustomData->getRequest()->getRequestInfo('headers'));
    }
}
