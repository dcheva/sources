<?php
header('Content-type: application/json');
echo json_encode(array(
    "success" => true,
    "data" => array(
        "call" => 'back'
    )));