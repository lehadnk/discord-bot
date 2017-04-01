<?php

/**
 * Connection.php
 * Creator: lehadnk
 * Date: 29/03/2017
 */
class App
{
    /**
     * @var \React\EventLoop\LoopInterface
     */
    public $loop;

    /**
     * @var WebhookListener
     */
    public $webhookListener;

    /**
     * @var \DataStructures\Guild[]
     */
    public $guilds = [];

    public function __construct($webhookPort = 1337)
    {
        $this->loop = React\EventLoop\Factory::create();
        $this->webhookListener = new WebhookListener($this->loop, $webhookPort);

        $this->registerPeriodicEvents();

        $this->run();
    }

    public function run() {
        echo "Server running at http://127.0.0.1:1337\n";
        $this->loop->run();
    }

    public function registerPeriodicEvents() {
        $this->addPeriodicRequest(60, 'UsersMeGuilds', function($guildsList) {
            $this->guilds = $guildsList;
            print_r($guildsList);
        });
    }

    public function addPeriodicRequest(int $interval, string $requestName, callable $onComplete, $fireNow = true) {
        $request = $this->requestFactory($requestName);
        $request->onComplete = $onComplete;
        $this->loop->addPeriodicTimer($interval, [$request, 'request']);
        if ($fireNow) {
            $request->request();
        }
    }

    /**
     * @param $name
     * @return \Requests\RequestAbstract
     */
    public function requestFactory($name) {
        $classname = "Requests\\$name";
        return new $classname(new DiscordHttpClientFacade($this->loop));
    }







    /********************************************************************************
     *                                 STATIC PART
     ********************************************************************************/





    /**
     * @var self
     */
    private static $app;

    public static function initialize() {
        self::$app = new self();
    }

    /**
     * @return self
     */
    public static function get() {
        return self::$app;
    }
}