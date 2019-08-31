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
//        if (false === extension_loaded('redis')) {
//            throw new \RuntimeException("the 'redis' extension is needed in order to use this session handler");
//        }
        //come from config
        $conf = [
            'host'   => $host,
            'port'   => $port
        ];
        if($enableTLS) {
            $conf['scheme'] = 'tls';
        }

        $this->redis = new Client($conf);
    }

    /**z
     * Close the session
     * @link https://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close()
    {
        // TODO: Implement close() method.
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
        // TODO: Implement read() method.
        return $this->redis->get($session_id);
    }

    public function write($session_id, $session_data)
    {
        return $this->redis->set($session_id,$session_data);
    }
}