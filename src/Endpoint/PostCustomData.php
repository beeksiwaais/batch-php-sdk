<?php

namespace Foodcheri\Batch\Endpoint;

use Foodcheri\Batch\Endpoint\AbstractEndpoint;
use Foodcheri\Batch\Request\CURL;

class PostCustomData extends AbstractEndpoint
{
    public function getMethod()
    {
        return 'POST';
    }

    public function getUri()
    {
        return 'https://api.batch.com/1.0/{api_key}/data/users(/{user_id}?)';
    }

    public function getOptions()
    {
        return [
            'overwrite' => true,
            'values' => null
        ];
    }



    public function buildParams(array $params)
    {
        parent::buildParams(['values' => $this->formatParam($params)]);

        return $this;
    }

    public function formatParam(array $params)
    {
        $formattedParams = [];

        foreach ($params as $option => $value) {
            if (is_array($value) && $this->isAssocArray($value)) {
                return ['id' => $option, 'update' => $this->formatParam($value)];
            }

            if (is_array($value)) {
                $formattedParams[sprintf('ut.%s', $option)] = $value;
            } else if ($value instanceof \DateTime) {
                $formattedParams[sprintf('date(u.%s)', $option)] = $value->getTimestamp();
            } else {
                $formattedParams[sprintf('u.%s', $option)] = $value;
            }
        }

        return $formattedParams;
    }

    /**
     *
     */
    public function isAssocArray(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
