<?php


namespace Foodcheri\Batch\Exception;


class UnknownEndpointException extends \RuntimeException
{
    public function __toString()
    {
        return sprintf('%s is not a valid endpoint', $this->message);
    }
}
