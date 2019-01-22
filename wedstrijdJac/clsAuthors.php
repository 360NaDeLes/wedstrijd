<?php

class authors {
    // I decided not to do a constructor, it would be more beneficial for me to use parameter for each function instead.

    public function connection() {
        $username = 'root';
        $pass = '';
        $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);
        return $conn;
    }

    public function getRandomAuthorsForNextRound($rondeId, $wedstrijdId) {
        // Gets winners from the previous round to make new fixtures
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT winner FROM `matches` WHERE wedstrijd =:wedstrijdId AND ronde =:rondeId AND winner IS NOT NULL ORDER BY RAND()");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId,
                ':rondeId'=>$rondeId
            )
        );
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAllAuthors() {
        // Gets all users
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT id, CONCAT(first_name, \" \", last_name) as FullName FROM authors");
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAuthorById($authorId) {
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT id, CONCAT(first_name, \" \", last_name) as FullName FROM authors WHERE id=:authorId");
        $sth->execute(ARRAY(
                ':authorId'=>$authorId
            )
        );
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}