<?php
namespace Controller;

use http\Response;
use Router\Request;
use Router\Url;

abstract class AbstractController
{
    protected $responseCode;

    public function response($statusCode, $responseData) {}

    public function getResponseCode()
    {
        return $this->responseCode;
    }

    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }
}
