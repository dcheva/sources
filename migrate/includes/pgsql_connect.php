<?php

/**
 * Description of pg_connect
 */
if (!@is_array($config)) {
    die('Hack off!');
}

/**
 * Postgres DB connect
 * @global array $alerts
 * @param array $config
 * @return resource pgsql link
 */
function pgsql_connect($config = []) {
    
    global $alerts;
    
    $pg_string = "host={$config['host']} "
            . "port={$config['port']} "
            . "dbname={$config['database']} "
            . "user={$config['username']} "
            . "password={$config['password']}";

    $dbconn = @pg_connect($pg_string)
            or $alerts[] = ['danger', 'Could not connect: ' . $pg_string];
    
    return $dbconn;
}
