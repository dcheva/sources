<?php

/**
 * Game class usage example.
 * @version 0.1.0
 */
require_once('game.class.php');

$game = new Game();
$game->setUser(41);
// var_dump($game->getToken());
// var_dump($game->getFrame());

//$game = new Game(rand());
//var_dump($game->getGameList());
//var_dump($game->getToken());
//
//var_dump($game->getUserList());
//
//var_dump($game->getUserNew(33, 300, 'EUR'));
//var_dump($game->getUser());
//
var_dump($game->getUserBet("drjoker_1.0", "some_bet_str"));
//var_dump($game->getUserBalance());
//
//var_dump($game->setUser(rand(0, 100)));
//
//var_dump($game->getUserBalanceChange(-10, 'USD'));

