<?php

namespace Foodcheri\Batch\Endpoint;

use Foodcheri\Batch\Request\CURL;

abstract class AbstractEndpoint
{
    protected $request;

    public function __construct()
    {
        $this->request = new CURL();
    }

    public function buildUrl(array $options)
    {
        if(empty($options['user_id'])) {
            $options['user_id'] = null;
        }

        $url = trim(preg_replace('/\((.{0,})\?\)/i', '$1', str_replace(array_map(function($str) { return sprintf('{%s}', $str); }, array_keys($options) ), $options, $this->getUri())), '/');

        $this->request->setMethod($this->getMethod());
        $this->request->setUrl($url);

        $this->request->addHeader('X-Authorization: ' . $options['rest_api_key']);

        return $this;
    }

    public function buildParams(array $params)
    {
        $this->request->setBody(json_encode(array_merge(
            $this->getOptions(),
            $params
        )));

        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function execute()
    {
        return $this->request->execute();
    }

    abstract public function getMethod();
    abstract public function getUri();
    abstract public function getOptions();
}
