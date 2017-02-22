<?php

namespace Foodcheri\Batch\Request;

class CURL
{
    public $url;
    public $method;
    public $code;
    public $time;
    public $header;

    protected $curl;
    protected $options = array();

    private $request = ['headers' => [], 'options' => []];


    public function setMethod($method)
    {
        $this->request['method'] = $method;

        return $this;
    }

    public function setUrl($url)
    {
        $this->request['url'] =  $url;
    }

    public function addHeader($header)
    {
        $this->request['headers'][] = $header;
    }

    public function setBody($body, $contentType = 'application/json')
    {
        $this->request['options'][CURLOPT_POSTFIELDS] = $body;
        $this->request['headers'][] = 'Content-type: ' . $contentType;
    }

    public function execute($clean = true)
    {
        $curl = curl_init();

        $this->request['options'] =
            $this->request['options'] +
            [
                CURLOPT_URL => $this->request['url'],
                CURLOPT_CUSTOMREQUEST => $this->request['method'],
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 40,
                CURLINFO_HEADER_OUT => true,
            ]
        ;

        if ('POST' == $this->request['method']) {
            $this->request['options'][CURLOPT_POST] = true;
        }

        $this->request['options'][CURLOPT_HTTPHEADER] = $this->request['headers'];

        // Make request
        curl_setopt_array($curl, $this->request['options']);
        $result = curl_exec($curl);
        $this->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->header = curl_getinfo($curl, CURLINFO_HEADER_OUT);

        if (true === $clean) {
            $this->request = ['headers' => [], 'options' => []];
        }


        return $result;
    }

    public function getLastCode()
    {
        return $this->code;
    }

    public function getLastResponseHeader()
    {
        return $this->header;
    }

    public function getRequestInfo($key)
    {
        return $this->request[$key];
    }
}
