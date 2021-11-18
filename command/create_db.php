<?php

declare(strict_types=1);

use src\Database\Connector;

require_once('src/Database/Connector.php');

$pdo = Connector::getPDO();

$pdo->query('
    CREATE TABLE IF NOT EXISTS `mood` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `mood` VARCHAR NOT NULL
    );
')->execute();

$pdo->query('
    CREATE TABLE IF NOT EXISTS `chat` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `pseudo` VARCHAR NOT NULL,
    `message` VARCHAR NOT NULL
    );
')->execute();

$pdo->query('
    CREATE TABLE IF NOT EXISTS `utilisateur` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `pseudo` VARCHAR NOT NULL,
    `pass` VARCHAR NOT NULL
    );
')->execute();