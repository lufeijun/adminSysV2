<?php


namespace GraphApp\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;

class ApiResponseException extends Exception implements ClientAware
{
    public function isClientSafe()
    {
        return false;
    }

    public function getCategory()
    {
        return ApiResponseException::class;
    }

    private $data = '';

    public function __construct($message, $data)
    {
        $this->data = $data;
        parent::__construct($message);
    }

    public function getData()
    {
        return $this->data;
    }
}
