<?php

/**
 * HttpClient.php
 * Creator: lehadnk
 * Date: 29/03/2017
 */
class HttpClientFacade
{
    /**
     * @var \React\Dns\Resolver\Resolver
     */
    static $dnsResolver;

    /**
     * @var \React\HttpClient\Factory
     */
    static $factory;

    /**
     * @var \React\HttpClient\Client
     */
    private $client;

    /**
     * @var string
     */
    protected $data = '';

    public static function initialize(\React\EventLoop\LoopInterface $loop) {
        $dnsResolverFactory = new React\Dns\Resolver\Factory();
        self::$dnsResolver = $dnsResolverFactory->createCached('8.8.8.8', $loop);

        self::$factory = new React\HttpClient\Factory();
    }

    public function __construct(\React\EventLoop\LoopInterface $loop)
    {
        if (self::$factory === null) {
            self::initialize($loop);
        }

        $this->client = self::$factory->create($loop, self::$dnsResolver);
    }

    protected function parseResponse() {
        return;
    }

    public function get($url, callable $callback, $headers = []) {
        $this->data = '';
var_dump($url);
        $httpRequest = $this->client->request('GET', $url, $headers);

        $httpRequest->on('response', function (\React\HttpClient\Response $httpResponse) use ($callback) {
            $httpResponse->on('data', function ($chunk) {
                $this->data .= $chunk;
            });
            $httpResponse->on('end', function() use ($callback) {
                $this->parseResponse();
                $callback($this->data);
            });
        });

        $httpRequest->end();
    }

    public function post($url, $postData, callable $callback, $headers = []) {
        $this->data = '';

        $headers['Content-Length'] = strlen($postData);
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        $httpRequest = $this->client->request('POST', $url, $headers);

        $httpRequest->on('response', function($response) use ($callback) {
            $response->on('data', function($data) use (&$buffer, $callback) {
                $this->data .= $data;
                if (strpos($data, PHP_EOL) !== false) {
                    $this->parseResponse();
                    $callback($this->data);
                }
            });
        });

        $httpRequest->end($postData);
    }
}