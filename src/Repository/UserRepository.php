<?php
declare(strict_types=1);

namespace src\Repository;

use src\Database\Connector;
use src\Entity\User;

class UserRepository{
    /**
     * @return array<User>
     */
    public function fetchAll(): array{
        $pdo = Connector::getPDO();
        $statement = $pdo->query('SELECT * FROM `utilisateur`');
        $statement->execute();

        $statement->setFetchMode(\PDO::FETCH_CLASS, User::class );
        $results = $statement->fetchAll();

        return $results;
    }

    public function insert(User $user){
        $pdo = Connector::getPDO();
        $statement = $pdo->prepare(('INSERT INTO `utilisateur` VALUES (null, :pseudo, :pass)'));
        $statement->bindParam('pseudo', $user->pseudo);
        $statement->bindParam('pass', password_hash($user->pass, PASSWORD_BCRYPT));
        $statement->execute();
    }

    public function fetchOne(string $pseudo){
        $pdo = Connector::getPDO();
        $statement = $pdo->prepare(('SELECT * FROM `utilisateur` WHERE `pseudo`= :pseudo'));
        $statement->bindParam('pseudo', $pseudo);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS, User::class );
        $result = $statement->fetch();
        
        return $result;
    }
}