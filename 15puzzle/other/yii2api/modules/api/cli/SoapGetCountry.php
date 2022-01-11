<?php

/**
 * Cli скрипт для получения информации о стране из SoapClient 
 * Использование: @ php SoapGetCountry.php
 * @author Дмитрий Чеусов <dmitry.cheva@gmail.com>
 * @category API/cli
 */
$client = new SoapClient('http://logos.dev/web/index.php?r=api/soap/get&t=' . mktime(true));
try {
    print_r($client->getCountry(616));
} catch (Exception $e) {
    echo $e->getMessage();
}
