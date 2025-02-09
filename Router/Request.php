<?php
namespace Router;

class Request
{
    const DOMAIN = "http://localhost/Event";
    const PROJECT_URI = "/Event";


    private static $instance;

    private $url;
    private $uri;
    private $getParams = [];
    private $postParams = [];
    private $filesParams = [];
    private $requestMethod;

    private function __construct()
    {
        $url = self::DOMAIN . $_SERVER['REQUEST_URI'];
        $this->setUrl($url);
        $serverUri=str_replace(self::PROJECT_URI,"",$_SERVER['REQUEST_URI']);
        $uri= (empty($serverUri) || $serverUri == "/") ? $serverUri : substr($serverUri, 1);
        $this->setUri($uri);
        $this->setGetParams($_GET);
        $this->setPostParams($_POST);
        $this->setRequestMethod($_SERVER["REQUEST_METHOD"]);
        $this->setFilesParams($_FILES);
    }

    public static function getInstance()
    {
        if (empty(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getFilesParams()
    {
        return $this->filesParams;
    }

    public function setFilesParams($filesParams)
    {
        $this->filesParams = $filesParams;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getGetParams()
    {
        return $this->getParams;
    }

    public function setGetParams($getParams)
    {
        $this->getParams = $getParams;
    }

    public function getPostParams()
    {
        return $this->postParams;
    }

    public function setPostParams($postParams)
    {
        $this->postParams = $postParams;
    }

    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }


}
