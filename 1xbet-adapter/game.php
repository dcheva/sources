<?php

/**
 * ELK controls
 * @version 0.1.0
 */

require_once('game.class.php');
require_once('Array2XML.class.php');

$game = new Game(simplexml_load_file("getaccount.xml"));
echo $game->getAccount();

$game = new Game(simplexml_load_file("getbalance.xml"));
echo $game->getBalance();

$game = new Game(simplexml_load_file("withdraw.xml"));
echo $game->withdraw();

$game = new Game(simplexml_load_file("deposit.xml"));
echo $game->deposit();
