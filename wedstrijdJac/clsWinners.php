<?php

class winners {
    // I decided not to do a constructor, it would be more beneficial for me to use parameter for each function instead.

    public function connection() {
        $username = 'root';
        $pass = '';
        $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);
        return $conn;
    }

    public function getWinnerByTournamentId($wedstrijdId) {
        // We check whether a tournament has a winner or not and return the value
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT COUNT(*) AS WinnersAmount FROM winners WHERE wedstrijdId=:wedstrijdId");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId
            )
        );
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function insertIntoWinners($wedstrijdId, $winner) {
        // Insert a winner into the table after a tournament
        $conn = $this->connection();
        $sth = $conn->prepare("INSERT INTO winners (wedstrijdId, authorId) VALUES (:wedstrijdId, :winner)");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId,
                ':winner'=>$winner
            )
        );
    }

    public function getWinnerNameByTournamentId($wedstrijdId) {
        // We retreive the winner by providing the tournament id and concat the names so we can use it
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT CONCAT(authors.first_name, \" \", authors.last_name) as ChampName FROM winners JOIN authors ON authors.id = winners.authorId WHERE wedstrijdId=:wedstrijdId");
        $sth->execute(ARRAY(
                    ':wedstrijdId'=>$wedstrijdId
                )
            );
        $champName = $sth->fetch(PDO::FETCH_ASSOC)['ChampName'];

        return $champName;
    }
}