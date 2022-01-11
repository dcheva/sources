<?php

/**
 * Description of worker
 */
$companies = @$_POST['companies'];
$file_name = @$_POST['filename'];

$response = [
    'status' => 'ok',
    'text' => '<div class="alert alert-success">Finished ' . $file_name
    . ' for companies ' . implode(',', $companies) . '</div>',
];

if (empty($companies)) {
    $response = [
        'status' => 'nok',
        'text' => "<div class='alert alert-danger'>"
        . "<b>Error.</b> You must select at least one company to proceed!</div>",
    ];
} elseif (!is_file($file_name)) {
    $response = [
        'status' => 'nok',
        'text' => "<div class='alert alert-danger'>"
        . "<b>Error.</b> File {$file_name} not exists or is not readable!</div>",];
}
// @todo more checks here
// start job
else {
    // Load config
    require_once './includes/config.php';
    // Connect to DB
    require_once './includes/pgsql_connect.php';
    // Connect to redis
    require_once './includes/redis_connect.php';
    redis_connect($config['redis']);
    $redis->setTimeout('sys_worker', $config['redis']['timeout']);

    if (!empty(end($alerts))) {
        $response = [
            'status' => 'nok',
            'text' => "<div class='alert alert-danger'>"
            . "<b>Error.</b>" . end($alerts)[1]];
        die(json_encode($response));
    };

    // get companies from DB
    $query = "SELECT * FROM company WHERE company_id IN("
            . join(', ', $companies) . ")";
    $result = pg_query(pgsql_connect($config['db']), $query);
    $a_companies = [];

    $redis->rpush('sys_worker', "Started migration: <a href='$file_name'>$file_name</a>");

    while ($dsn = pg_fetch_assoc($result)) {

        // Dump
        $details = ( date("H:i:s ") . "STARTED<br>");

        // get filename from migration name (better use hidden input from step 2)
        $new_version = substr($file_name, 8, 5);
        $dump_name = "./dump/" . $new_version . "_" . $dsn['database']
                . "_" . date("Y-m-d") . ".sql";
        $logfile = "./dump/log." . date("Y-m-d") . ".log";

        $command = "export PGPASSWORD={$dsn['password']} "
                . "&& pg_dump "
                . "-h {$dsn['host']} "
                . "-p {$dsn['port']} "
                . "-U {$dsn['username']} "
                . "-d {$dsn['database']} "
                . "-f $dump_name";

        file_put_contents($logfile, date("H:i:s ") . $command . "\n", FILE_APPEND | LOCK_EX);
        system($command . " &>> $logfile ");
        $details .= ( date("H:i:s ") . "Finished dump: <a href='$dump_name'>$dump_name</a><br>");

        // Migrate
        $details .= ( date("H:i:s ") . "Started migration<br>");
        $pgconn = pgsql_connect($dsn);

        $m_query = '';
        ////////////////////////////////////// for tests
        // wrong query (catch errors)
        // $m_query .= "INSERT INTO bad_table VALUES(1,2,3);";
        // right query (no errors)
        // $m_query .= "SELECT * FROM companies_clients";

        // real migration query (!BE CAREFUL!)
        $m_query = file_get_contents($file_name);

        pg_send_query($pgconn, $m_query);
        $m_result = pg_get_result($pgconn);
        $m_error = pg_result_error($m_result);

        // redis hashes fo results
        $hash = 'sys_worker_status:' . $dsn['company_id'];
        $redis->delete($hash);

        // Check and Update info or Error
        if (!empty($m_error)) {
            // restore dump (see ./dump/*.log)
            $status = 'error ' . $new_version;
            $details .=  date("H:i:s ") . trim($m_error) . "<br>";

            $command = "export PGPASSWORD={$dsn['password']} "
                    . "&& psql "
                    . "-h {$dsn['host']} "
                    . "-p {$dsn['port']} "
                    . "-U {$dsn['username']} "
                    . "-d {$dsn['database']}"
                    . " < $dump_name";

            file_put_contents($logfile, date("H:i:s ") . $command . "\n", FILE_APPEND | LOCK_EX);
            system($command . " &>> $logfile ");

            $details .= ( date("H:i:s ") . "Restored dump: $dump_name | see <a href='$logfile'>$logfile</a>");
        } else {
            // udate company with $new_version
            $u_query = "UPDATE company SET version = '{$new_version}' "
                    . "WHERE database = '{$dsn['database']}'";
            pg_query(pgsql_connect($config['db']), $u_query);

            $status = 'success ' . $new_version;
            $details .= date("H:i:s ") . "SUCCESS";
        }
        $redis->hMset($hash, [
            'database' => $dsn['database'],
            'version' => $new_version,
            'status' => $status,
            'details' => $details,
        ]);
    }
    $redis->rpush('sys_worker', "Finished migration: $file_name");
}

die(json_encode($response));
