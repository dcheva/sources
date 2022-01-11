<?php

/**
 * Description of OT-68: http://jira.iterios.com/browse/OT-68
 * Client's DB migration automation
 */
// Load config
require_once './includes/config.php';

$alerts = [];

// Connect to DB
require_once './includes/pgsql_connect.php';

$query = "SELECT * FROM company";
$result = pg_query(pgsql_connect($config['db']), $query);
$a_companies = [];
while ($row = pg_fetch_assoc($result)) {
    $a_companies[$row['company_id']] = $row;
}

$step = (int) @$_POST['step'] + 1;

// Step 1. Upload migration
if ($step == 1) {
    $alerts[] = ['info', "<b>Step 1.</b> Upload migration SQL."];
}

// Check form data
if ($step == 2) {
    if ($_FILES['file']['type'] == '') {
        $alerts[] = ['danger', "<b>Error:</b> You must upload application/sql file "
            . "()"];
    }
    if (empty($_POST['version'])) {
        $alerts[] = ['danger', "<b>Error:</b> You must select version"];
    }
    if (!empty($alerts)) {
        $step = 1;
    }
}

// Step 2. Select to update
if ($step == 2) {

    // save sql file
    $file_name = "./files/" . preg_replace('/[^0-9]/', '_', $_POST['version']) . "_"
            . preg_replace('/[^0-9A-Za-z\.\_\-]/', '_', $_FILES['file']['name']);
    $tmp_name = $_FILES['file']['tmp_name'];
    move_uploaded_file($tmp_name, $file_name);

    // Compare versions and mark checked
    foreach ($a_companies as $dsn) {
        // Compare versions and mark checked
        if (get_new_version($_POST['version']) > get_current_version($dsn['version'])) {
            $dsn['checked'] = 'checked';
        } else {
            $dsn['checked'] = '';
        }

        // Beautify version (1_0_0 -> 1.0.0)
        $dsn['version'] = preg_replace('/[^0-9]/', '.', $dsn['version']);
        $c_config[$dsn['company_id']] = $dsn;
    }

    $alerts[] = ['info', "<b>Step 2.</b> Select companies to migrate up to "
        . "<b>{$_POST['version']}</b> ($file_name)"];
}

/*
 *  Step 3. Sit back and enjoy
 *  JQuery Ajax will ping worker.php
 */
if ($step == 3) {
    $file_name = @$_POST['file'];
    $companies = @$_POST['migrate'];
    $alerts[] = ['info', "<b>Step 3.</b> Sit back and enjoy!"];
}

// Render template
require_once "./templates/main.tpl";
