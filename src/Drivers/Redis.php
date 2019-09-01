<?php

namespace Prophecy\DDriver\Drivers;

use Predis\Client;

class Redis implements \SessionHandlerInterface {

    /**
     * @var Client
     */
    private $redis;

    public function __construct(string $host = '127.0.0.1', int $port = 6379 ,bool $enableTLS = false)
    {
        $conf = [
            'host'   => $host,
            'port'   => $port
        ];
        if($enableTLS) {
            $conf['scheme'] = 'tls';
        }

        $this->redis = new Client($conf);
    }

    public function close()
    {
        $this->redis->disconnect();

        return true;
    }

    public function destroy($key)
    {
        return $this->redis->del((array)$key);
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    public function open($save_path, $name)
    {
        $this->redis->connect();

        return true;
    }

    public function read($session_id)
    {
        return $this->redis->get($session_id);
    }

    public function write($session_id, $session_data)
    {
        return $this->redis->set($session_id,$session_data);
    }
}