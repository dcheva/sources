<?php

/** 
 * File for phpdocx execution script
 */

$path = __DIR__ . '/../../phpdocx';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
$path = __DIR__ . '/../../phpdocx/classes';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'Cached.php';

error_reporting(0);

$output_file = $argv[1];
$cached = new Cached;
$data = unserialize($cached->getCached(md5($output_file)));

if (empty($data)) {
    die(json_encode(['Message'=>"Required attributes is not placed \n" . print_r($output_file)]));
}
if (empty($data['data'])) {
    die(json_encode(['Message'=>'Data is not placed']));
}

//require_once __DIR__ . '/../../phpdocx/lib/log4php/Logger.php';
require_once 'CreateDocx.inc';

$o_data = json_decode($data['data']);
$a_data = [];
foreach($o_data as $k=>$v) {
    $a_data[$k] = $v;
}

if (empty($data)) {
    die(json_encode(['Message'=>"Data is not placed"]));
}

$docx = new CreateDocxFromTemplate($data['tpl']);
$variables = $docx->getTemplateVariables();
$docx->processTemplate($variables);

$options = array('parseLineBreaks' =>true);
$docx->setTemplateSymbol('$');

$docx->replaceVariableByText($a_data, $options);
$docx->createDocx($output_file);