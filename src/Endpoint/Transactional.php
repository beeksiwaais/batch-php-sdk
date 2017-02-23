<?php

namespace Foodcheri\Batch\Endpoint;

use Foodcheri\Batch\Request\CURL;

class Transactional extends AbstractEndpoint
{
    public function getMethod()
    {
        return 'POST';
    }

    public function getUri()
    {
        return 'https://api.batch.com/1.1/{api_key}/transactional/send';
    }

    public function getOptions()
    {
        return [
            'group_id'   => null,
            'recipients' => null,
            'message'    => null
        ];
    }
}
