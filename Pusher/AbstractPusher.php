<?php

namespace Gos\Bundle\WebSocketBundle\Pusher;

use Gos\Bundle\WebSocketBundle\Pusher\Serializer\MessageSerializer;
use Gos\Bundle\WebSocketBundle\Router\WampRouter;

abstract class AbstractPusher implements PusherInterface
{
    /** @var  MessageSerializer */
    protected $serializer;

    /** @var  Array */
    private $config;

    /** @var  WampRouter */
    protected $router;

    /** @var  bool */
    protected $connected = false;

    protected $connection;

    /**
     * @param string $data
     *
     * @return string
     */
    abstract protected function doPush($data);

    /**
     * @param MessageSerializer $serializer
     */
    public function setSerializer(MessageSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param WampRouter $router
     */
    public function setRouter(WampRouter $router)
    {
        $this->router = $router;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param bool|true $bool
     */
    public function setConnected($bool = true)
    {
        $this->connected = $bool;
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * @param array|string $data
     * @param string       $routeName
     * @param array[]      $routeParameters
     *
     * @return string|\Symfony\Component\Serializer\Encoder\scalar
     */
    public function push($data, $routeName, $routeParameters)
    {
        $chanel = $this->router->generate($routeName, $routeParameters);
        $message = new Message($chanel, $data);

        return $this->doPush($this->serializer->serialize($message));
    }
}