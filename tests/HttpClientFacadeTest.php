<?php

/**
 * HttpClientFacadeTest.php
 * Creator: lehadnk
 * Date: 29/03/2017
 */
class HttpClientFacadeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClientFacade
     */
    private $client;

    public function setUp() {
        $loop = $loop = React\EventLoop\Factory::create();
        $this->client = new HttpClientFacade($loop);


    }

    public function testGet() {
        $this->client->get('https://github.com/', function($data) {
            //$response->write($data);
            //$response->end();
        });
    }

    public function testPost() {
        $this->client->post('https://httpbin.org/post', "id=1", function($data) {
            //$response->write($data);
            //$response->end();
        });
    }
}
