<?php

/**
 * WebhookRequest.php
 * Creator: lehadnk
 * Date: 29/03/2017
 */
class WebhookListener
{
    /**
     * @var \React\EventLoop\LoopInterface
     */
    private $loop;

    /**
     * @var \React\Socket\Server
     */
    private $httpServer;

    /**
     * @var \React\Http\Server
     */
    private $socket;

    public function __construct(\React\EventLoop\LoopInterface $loop, $port)
    {
        $this->loop = $loop;
        $this->socket = new React\Socket\Server($loop);
        $this->httpServer = new React\Http\Server($this->socket, $loop);
        $this->httpServer->on('request', [$this, 'handleRequest']);
        $this->socket->listen($port);
    }

    public function handleRequest(\React\Http\Request $request, \React\Http\Response $response) {
        $response->writeHead(200, array('Content-Type' => 'text/html'));

        $bot = new DiscordHttpClientFacade($this->loop);
        $bot->get('/users/@me/guilds', function($data) use ($response) {
            $response->end(print_r($data, true));
        });
    }
}