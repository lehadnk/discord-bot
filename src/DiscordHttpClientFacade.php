<?php

/**
 * DiscordClientFacade.php
 * Creator: lehadnk
 * Date: 29/03/2017
 */
class DiscordHttpClientFacade extends HttpClientFacade
{
    private $baseUrl = 'https://discordapp.com/api';

    private function getBaseHeaders() {
        return [
            'Authorization' => 'Bot '.BOT_TOKEN,
            'User-Agent' => 'DiscordBot TriviaBot/0.1',
        ];
    }

    public function get($url, callable $callback, $headers = [])
    {
        $headers = array_replace($this->getBaseHeaders(), $headers);
        parent::get($this->baseUrl.$url, $callback, $headers);
    }

    public function post($url, $postData, callable $callback, $headers = [])
    {
        $postData = json_encode($postData);
        $headers = array_replace($this->getBaseHeaders(), $headers);
        parent::post($this->baseUrl.$url, $postData, $callback, $headers);
    }

    protected function parseResponse()
    {
        $this->data = json_decode($this->data);
    }
}