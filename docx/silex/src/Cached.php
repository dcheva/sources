<?php

/**
 * File for Cached class
 */

/**
 * Class for manipulate information for application throw cache
 */
class Cached
{
    private $config;
    private $conn;

    function __construct()
    {
        $this->config = [
            'host' => '127.0.0.1',
            'port' => '6379',
            'database' => '1',
            'prefix' => 'sys_',
            'timeout' => 600,
        ];
        $this->conn = $this->redis_connect();
    }

    /**
     * Redis DB connect
     * @return resource redis link
     */
    function redis_connect()
    {
        $redis = new Redis();

        @$redis->connect($this->config['host'], $this->config['port'])
                or die(json_encode(['Message'=>"Could not connect toredis: "
                        . "{$this->config['host']}:{$this->config['port']}"]));

        $redis->select($this->config['database']);

        return $redis;
    }

    /** 
     * Set cached key/value
     * 
     * @param string $key
     * @param mixed $value
     */
    function setCached($key, $value)
    {
        $this->conn->setex($key, $this->config['timeout'], $value);
    }

    /** 
     * Get cached by key
     * 
     * @param string $key
     * @return mixed
     */
    function getCached($key)
    {
        return $this->conn->get($key);
    }

}
