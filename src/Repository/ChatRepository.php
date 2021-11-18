<?php
declare(strict_types=1);

namespace src\Repository;

use src\Database\Connector;
use src\Entity\Chat;

class ChatRepository{
    /**
     * @return array<Chat>
     */
    public function fetchAll(): array{
        $pdo = Connector::getPDO();
        $statement = $pdo->query('SELECT * FROM `chat`');
        $statement->execute();

        $statement->setFetchMode(\PDO::FETCH_CLASS, Chat::class );
        $results = $statement->fetchAll();

        return $results;
    }

    public function insert(Chat $chat){
        $pdo = Connector::getPDO();
        $statement = $pdo->prepare(('INSERT INTO `chat` VALUES (null, :pseudo, :chat)'));
        $statement->bindParam('pseudo', $chat->pseudo);
        $statement->bindParam('chat', $chat->message);
        $statement->execute();
    }
}