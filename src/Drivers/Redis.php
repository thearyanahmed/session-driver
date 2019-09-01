<?php

namespace Prophecy\DDriver\Drivers;

use Predis\Client;

class Redis implements \SessionHandlerInterface {

    /**
     * @var Client
     */
    private $redis;

    /**
     * Redis constructor.
     * @param string $host
     * @param int $port
     * @param bool $enableTLS
     */
    public function __construct(string $host = '127.0.0.1', int $port = 6379 , bool $enableTLS = false)
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
    /**
     * @param string $save_path
     * @param string $name
     * @return bool
     */
    public function open($save_path, $name)
    {
        $this->redis->connect();

        return true;
    }
    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool|mixed
     */
    public function write($session_id, $session_data)
    {
        return $this->redis->set($session_id,$session_data);
    }
    /**
     * @param string $session_id
     * @return string
     */
    public function read($session_id)
    {
        return $this->redis->get($session_id);
    }
    /**
     * @param string $key
     * @return bool|int
     */
    public function destroy($key)
    {
        return $this->redis->del((array)$key);
    }
    /**
     * @param int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        return true;
    }
    /**
     * @return bool
     */
    public function close()
    {
        $this->redis->disconnect();

        return true;
    }
}