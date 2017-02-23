<?php

namespace Foodcheri\Batch;

use Foodcheri\Batch\Exception\UnknownEndpointException;
use Foodcheri\Batch\Facade;
use Foodcheri\Batch\Exception\AuthentificationException;

class Client
{
    private $config;

    public function __construct(array $config = [])
    {
        $config = array_merge([
            'api_key'      => null,
            'rest_api_key' => null
        ], $config);

        if (!$config['api_key'] || !$config['rest_api_key']) {
            throw new AuthentificationException('Required api_key and/or rest_api_key field are missing');
        }

        $this->config = $config;
    }

    public function getApi($name)
    {
        switch ($name) {
            case 'transactional' :
                return new Facade\Transactional($this->config);
            case 'custom_data' :
                return new Facade\CustomData($this->config);
            default :
                throw new UnknownEndpointException($name);
        }
    }

    public function getConfig()
    {
        return $this->config;
    }
}
