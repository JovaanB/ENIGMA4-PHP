<?php

declare(strict_types=1);

namespace src\Repository;

use src\Database\Connector;
use src\Entity\Message;

class MessageRepository {
    public function fetchAll(): array {
        $pdo = Connector::getPDO();
        $statement = $pdo->query('SELECT * FROM `message`');
        $statement->setFetchMode(\PDO::FETCH_CLASS, Message::class);
        return $statement->fetchAll();
    }

    public function insert(Message $message) {
        $pdo = Connector::getPDO();
        $statement = $pdo->prepare('INSERT INTO `message` VALUES(null, :pseudo, :mess)');
        $statement->bindParam('pseudo', $message->pseudo);
        $statement->bindParam('mess', $message->mess);
        $statement->execute();
    }
}