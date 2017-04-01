<?php
/**
 * RequestInterface.php
 * Creator: lehadnk
 * Date: 29/03/2017
 */

namespace Requests;


abstract class RequestAbstract
{

    /**
     * @var \DiscordHttpClientFacade
     */
    protected $client;

    /**
     * @var callable
     */
    public $onComplete;

    /**
     * @var callable
     */
    public $onError;

    public function __construct(\DiscordHttpClientFacade $client)
    {
        $this->client = $client;
    }

    protected function get($url)
    {
        $this->client->get($url, function($data) { $this->onComplete($data); });
    }

    protected function post($url, $postData)
    {
        $this->client->post($url, $postData, [$this, 'onComplete']);
    }

    protected function onComplete($data)
    {
        $this->onComplete;
    }

    protected function callbackComplete($data)
    {
        $function = $this->onComplete;
        $function($data);
    }

    public abstract function request();
}