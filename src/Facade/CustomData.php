<?php


namespace Foodcheri\Batch\Facade;

use Foodcheri\Batch\Endpoint;

class CustomData
{
    private $config;
    private $request;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function set($params, $userId = null)
    {
        $endpoint = new Endpoint\PostCustomData();

        $this->request = $endpoint
            ->buildUrl(array_merge($this->config, ['user_id' => $userId]))
            ->buildParams($params)
            ->getRequest();

        return $this->request->execute();
    }

    public function getRequest()
    {
        return $this->request;
    }
}
