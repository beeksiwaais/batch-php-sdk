<?php


namespace Foodcheri\Batch\Facade;

use Foodcheri\Batch\Endpoint;
use Foodcheri\Batch\Request\CURL;

class Transactional
{
    private $config;
    private $request;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function push($recipients, $title, $body, $groupId, $options = [])
    {
        $endpoint = new Endpoint\Transactional();

        if (!is_array($recipients)) {
            $recipients = [$recipients];
        }

        $this->request = $endpoint
            ->buildUrl($this->config)
            ->buildParams(array_merge([
                'group_id' => $groupId,
                'recipients' => ['custom_ids' => $recipients],
                'message' => [
                    'title' => $title,
                    'body' => $body
                ]
            ], $options))
            ->getRequest();

        return $this->request->execute();
    }

    public function getRequest()
    {
        return $this->request;
    }

}
