<?php
namespace http;

use Exception\ResponseException;

class Response
{
    const HTTP_OK = 200;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_NOT_FOUND = 404;
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

    private static $instance;

    private $headers = [];
    private $body;
    private $statusCode;
    private $statusMessages = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        404 => 'Not Found',
        406 => 'Not Acceptable',
        500 => 'Internal Server Error'
    ];

    private function __construct( $statusCode = 200 ) {}

    public static function getInstance(
        $statusCode = 200
    ) {
        if (empty(self::$instance)) {
            self::$instance = new self(
                $statusCode
            );
        }

        return self::$instance;
    }

    public function mapStatusMessages($statusCode)
    {
        if (!array_key_exists($statusCode, $this->getStatusMessages())) {
            throw new ResponseException();
        }

        return $this->getStatusMessages()[$statusCode];
    }

    public function json()
    {
        $this->body = json_encode($this->getBody());
    }

    public function setHeaders(array $headers)
    {
      if (empty($headers)) {
          throw  new ResponseException();
      }
      foreach ($headers as $key => $header) {
            $this->setHeader($key, $header);
      }
    }

    public function setBody($body)
    {
        if (empty($body)) {
            throw  new ResponseException();
        }
        $this->body = $body;
    }

    public function send()
    {
        header_remove();
        http_response_code($this->getStatusCode());
        $this->sendHeaders();
        $this->sendBody();
        die();
    }

    private function sendHeaders()
    {
        foreach ($this->headers as $key => $value) {
            header($key . ":" . $value);
        }
    }

    private function sendBody()
    {
        echo $this->getBody();
    }

    public function setHeader(
        $key,
        $value
    )
    {
        $this->headers[$key] = $value;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function redirect($url)
    {
        header("Location: " . $url);
        die();
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getStatusMessages()
    {
        return $this->statusMessages;
    }

    public function setStatusMessages($statusMessages)
    {
        $this->statusMessages = $statusMessages;
    }

}
