<?php
declare(strict_types=1);

namespace src\Repository;

use src\Database\Connector;
use src\Entity\Mood;

class MoodRepository{
    /**
     * @return array<Mood>
     */
    public function fetchAll(): array{
        $pdo = Connector::getPDO();
        $statement = $pdo->query('SELECT * FROM `mood`');
        $statement->execute();

        $statement->setFetchMode(\PDO::FETCH_CLASS, Mood::class );
        $results = $statement->fetchAll();

        return $results;
    }

    public function insert(Mood $mood){
        $pdo = Connector::getPDO();
        $statement = $pdo->prepare(('INSERT INTO `mood` VALUES (null, :mood)'));
        $statement->bindParam('mood', $mood->mood);
        $statement->execute();
    }


    public function delete(string $id){
        $pdo = Connector::getPDO();
        $statement = $pdo->prepare('DELETE FROM `mood` WHERE id = :id');
        $statement->bindParam('id', $id);
        $statement->execute();
    }
}