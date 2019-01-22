<?php

class matches {
    // I decided not to do a constructor, it would be more beneficial for me to use parameter for each function instead.

    public function connection() {
        // Basic connection function
        $username = 'root';
        $pass = '';
        $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);
        return $conn;
    }

    public function checkWinnersPerRoundId($wedstrijdId, $rondeId) {
        // We check if winner is NULL or not, we use this function to determine whether we have one match left and it has a winner A.K.A we have a champion
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT IFNULL(winner, null) as winner FROM matches WHERE wedstrijd =:wedstrijdId AND ronde=:rondeId");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId,
                ':rondeId'=>$rondeId
            )
        );
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function updateMatches($winner, $matchId, $wedstrijdId, $rondeId) {
        // Update the winners of each individual match
        $conn = $this->connection();
        $sth = $conn->prepare("UPDATE matches SET winner=:winner WHERE matchid=:matchid AND wedstrijd=:wedstrijdId");
        $sth->execute(ARRAY(
                ':winner'=>$winner,
                ':matchid'=>$matchId,
                ':wedstrijdId'=>$wedstrijdId
            )
        );
        header("Location: matches.php?wedstrijd={$wedstrijdId}&ronde={$rondeId}");
    }

    public function insertIntoMatches($wedstrijdId, $newRonde, $author1, $author2) {
        // We make fixtures from the users we got from "getRandomAuthorsForNextRound()" and insert those into matches
        $conn = $this->connection();
        $sth = $conn->prepare("INSERT INTO matches (wedstrijd, ronde, author_1, author_2) VALUES (:wedstrijd, :ronde, :author1, :author2)");
        $sth->execute(ARRAY(
                ':wedstrijd'=>$wedstrijdId,
                ':ronde'=>$newRonde,
                ':author1'=> $author1,
                ':author2'=> $author2
            )
        );
    }

    public function selectDistinctMatches($wedstrijdId) {
        // We get all the rounds so we can make a anchor element for them
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT DISTINCT(ronde) FROM matches WHERE wedstrijd=:wedstrijdId");
        $sth->execute(ARRAY(
                    ':wedstrijdId'=>$wedstrijdId
                )
            );
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getUsersByRound($wedstrijdId, $rondeId) {
        // Basically a huge query which joins two tables
        // In this query we get all the users from the tournament and the round we are currently in, we join the authors table and concat the names so we can use it
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT matches.matchId, matches.wedstrijd, matches.ronde, a1.id as IdOne, CONCAT(a1.first_name, \" \", a1.last_name) as FullName, a2.id as IdTwo, CONCAT(a2.first_name, \" \", a2.last_name) AS FullName2, winner FROM `matches` INNER JOIN authors a1 on a1.id = matches.author_1
        INNER JOIN authors a2 on a2.id = matches.author_2 WHERE wedstrijd = :wedstrijdId AND ronde=:rondeId");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId,
                ':rondeId'=>$rondeId
            )
        );
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getTotalAmountOfMatchesPerRound($wedstrijdId, $rondeId) {
        // Counts the total amount of matches per round
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT COUNT(*) as TotalAmountofRows FROM matches WHERE wedstrijd = :wedstrijdId AND ronde=:rondeId");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId,
                ':rondeId'=>$rondeId
            )
        );
        $totalAmountOfRows = $sth->fetch(PDO::FETCH_ASSOC)['TotalAmountofRows'];

        return $totalAmountOfRows;
    }

    public function getTotalAmountOfWinnersPerRound($wedstrijdId, $rondeId) {
        // Counts the winners of each match per round
        $conn = $this->connection();
        $sth = $conn->prepare("SELECT COUNT(*) as NumberOfWinners FROM `matches` WHERE wedstrijd =:wedstrijdId AND winner IS NOT NULL AND ronde=:rondeId");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId,
                ':rondeId'=>$rondeId
            )
        );
        $totalWinners = $sth->fetch(PDO::FETCH_ASSOC)['NumberOfWinners'];

        return $totalWinners;
    }
}