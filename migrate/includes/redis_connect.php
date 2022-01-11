<?php

/**
 * Description of redis_connect
 */
if (!@is_array($config)) {
    die('Hack off!');
}

$redis = new Redis();

/**
 * Redis DB connect
 * @global array $alerts
 * @param array $config
 * @return resource redis link
 */
function redis_connect($config = []) {

    global $alerts, $redis;

    $redis->connect($config['host'], $config['port'])
            or $alerts[] = ['danger', "Could not connect toredis: "
        . "{$config['host']}:{$config['port']}"];
        
    $redis->select($config['database']);
}
