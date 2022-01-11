<?php

/**
 * Description of listener
 */
// Load config
require_once './includes/config.php';
// Connect to redis
require_once './includes/redis_connect.php';
redis_connect($config['redis']);

// read sys_worker log
$result = $redis->lrange('sys_worker', 0, 999);

// read db statuses
$keys = $redis->keys('sys_worker_status:*');
$table = "<table class='table table-striped table-bordered table-hover'>";
$table .= "<tr>";
$table .= "<th>" . ('database') . "</th>";
$table .= "<th>" . ('current version') . "</th>";
$table .= "<th>" . ('last status') . "</th>";
$table .= "<th>" . ('details') . "</th>";
$table .= "</tr>";

foreach ($keys as $hash) {
    $table .= "<tr>";
    $mresult = $redis->hGetAll("$hash");
    $table .= "<td>" . ($mresult['database']) . "</td>";
    $table .= "<td>" . ($mresult['version']) . "</td>";
    $table .= "<td>" . ($mresult['status']) . "</td>";
    $table .= "<td><small>" . ($mresult['details']) . "</td>";
    $table .= "</tr>";
}
$table .= "<table>";

if (empty($result)) {
    $text = "<div class = 'alert alert-warning'>No sys_worker logs found!</div>";
} else {
    $text = "<div class = 'alert alert-success'>";
    foreach ($result as $row) {
        $string = print_r($row, true);
        $text .= "{$string}<br>";
    }
    $text .= "</div>";
};

$response = [
    'status' => 'ok',
    'text' => $text,
    'table' => $table,
];

die(json_encode($response));
