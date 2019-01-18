<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $username = 'root';
    $pass = '';
    $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);

    echo"<pre>";print_r($_POST);echo"</pre>";

    $HTML = "<nav>
                <a href='index.php'>Index</a>
                <a href='matches.php'>Matches</a>
                <a href='wedstrijden.php'>Wedstrijden</a>
                <a href='user.php'>User</a>
            </nav>";

    if(array_key_exists('wedstrijd', $_GET) && array_key_exists('ronde', $_GET) && isset($_GET)) {
        $wedstrijdId = $_GET['wedstrijd'];
        $rondeId = $_GET['ronde'];
        $finale = false;
        $champion = null;
        $tourneyWinner = "";
        $disableButtons = false;

        $sth = $conn->prepare("SELECT COUNT(*) AS WinnersAmount FROM winners WHERE wedstrijdId=:wedstrijdId");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId
            )
        );

        $result = $sth->fetch(PDO::FETCH_ASSOC);
        print_r($result);
        if($result['WinnersAmount'] == 1) {
            $disableButtons = true;
            $champion = true;
        }

        $sth = $conn->prepare("SELECT IFNULL(winner, null) as winner FROM matches WHERE wedstrijd =:wedstrijdId AND ronde=:rondeId");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId,
                ':rondeId'=>$rondeId
            )
        );
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        print_r($result);
        if(count($result) == 1) {
            $finale = true;
            if(is_numeric($result[0]['winner'])) {
                $tourneyWinner = true;
                $tournamentWinner = $result[0]['winner'];
            }
        } 
        if(isset($_POST) && count($_POST) > 0 && array_key_exists('winner', $_POST)) {
            $winner = $_POST['author'];
            $matchid = $_POST['winner'];
            $sth = $conn->prepare("UPDATE matches SET winner=:winner WHERE matchid=:matchid AND wedstrijd=:wedstrijdId");
            $sth->execute(ARRAY(
                    ':winner'=>$winner,
                    ':matchid'=>$matchid,
                    ':wedstrijdId'=>$wedstrijdId
                )
            );
            header("Location: matches.php?wedstrijd={$wedstrijdId}&ronde={$rondeId}");
        } elseif(isset($_POST) && count($_POST) > 0 && array_key_exists('startNextRound', $_POST) && isset($_GET['ronde'])) {
            $wedstrijdId = $_GET['wedstrijd'];
            $rondeId = (int)$_GET['ronde'];
            $sth = $conn->prepare("SELECT winner FROM `matches` WHERE wedstrijd =:wedstrijdId AND ronde =:rondeId AND winner IS NOT NULL ORDER BY RAND()");
            $sth->execute(ARRAY(
                    ':wedstrijdId'=>$wedstrijdId,
                    ':rondeId'=>$rondeId
                )
            );
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);

            $limit = count($result);
            $endForeach = $limit - 1;
            $newRonde = $_GET['ronde'] + 1;
            $ii = 0;
            
            echo"<pre>";print_r($result);echo"</pre>";

            foreach($result as $authors) {
                $sth = $conn->prepare("INSERT INTO matches (wedstrijd, ronde, author_1, author_2) VALUES (:wedstrijd, :ronde, :author1, :author2)");
                $sth->execute(ARRAY(
                        ':wedstrijd'=>$wedstrijdId,
                        ':ronde'=>$newRonde,
                        ':author1'=> $result[$ii]['winner'],
                        ':author2'=> $result[$ii + 1]['winner']
                    )
                );
                $ii = $ii + 2;
                if($ii > $endForeach) {
                    // End the foreach because we don't need a million errors
                    break;
                }
            }
            header("Location: matches.php?wedstrijd=".$wedstrijdId."&ronde=".$newRonde);
        } elseif(isset($_POST) && count($_POST) > 0 && array_key_exists('endTournament', $_POST)) {
            $wedstrijdId = $_GET['wedstrijd'];
            $rondeId = $_GET['ronde'];
            $winner = $_POST['endTournament'];
            $sth = $conn->prepare("INSERT INTO winners (wedstrijdId, authorId) VALUES (:wedstrijdId, :winner)");
            $sth->execute(ARRAY(
                    ':wedstrijdId'=>$wedstrijdId,
                    ':winner'=>$winner
                )
            );
            header("Location: matches.php?wedstrijd={$wedstrijdId}&ronde={$rondeId}");
        }

        // We get all the rounds so we can make a anchor element for them
        $sth = $conn->prepare("SELECT DISTINCT(ronde) FROM matches WHERE wedstrijd=:wedstrijdId");
        $sth->execute(ARRAY(
                    ':wedstrijdId'=>$wedstrijdId
                )
            );
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        $RONDE = "";
        foreach($result as $ronde) {
            foreach($ronde as $key=>$value) {
                $RONDE .= "<a href=\"matches.php?wedstrijd=$wedstrijdId&ronde=$value\">Ronde: $value</a><br />";
            }
        }

        // Basically a huge query which joins two tables
        $sth = $conn->prepare("SELECT matches.matchId, matches.wedstrijd, matches.ronde, a1.id as IdOne, CONCAT(a1.first_name, \" \", a1.last_name) as FullName, a2.id as IdTwo, CONCAT(a2.first_name, \" \", a2.last_name) AS FullName2, winner FROM `matches` INNER JOIN authors a1 on a1.id = matches.author_1
        INNER JOIN authors a2 on a2.id = matches.author_2 WHERE wedstrijd = :wedstrijdId AND ronde=:rondeId");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId,
                ':rondeId'=>$rondeId
            )
        );
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(count($results) > 0) {
            if($finale) {
                $finaleTekst = "<b>FINALE RONDE</b>";
            } else {
                $finaleTekst = "";
            }
            if($champion != null) {
                $sth = $conn->prepare("SELECT CONCAT(authors.first_name, \" \", authors.last_name) as ChampName FROM winners JOIN authors ON authors.id = winners.authorId WHERE wedstrijdId=:wedstrijdId");
                $sth->execute(ARRAY(
                            ':wedstrijdId'=>$wedstrijdId
                        )
                    );
                $champName = $sth->fetch(PDO::FETCH_ASSOC)['ChampName'];
                $champTekst = "<b>{$champName} heeft het toernooi gewonnen</b>";
            } else {
                $champTekst = "";
            }
            $HTML.= "{$finaleTekst}<br />
                    {$RONDE}
                    <table style=\"text-align: center\">
                        <th>MatchId</th>
                        <th>Wedstrijd</th>
                        <th>Ronde</th>
                        <th>Author 1</th>
                        <th>VS</th>
                        <th>Author 2</th>";
            if(!$disableButtons) {
            $HTML .=   "<th colspan=2>Winner</th>";
            }
            foreach($results as $result) {
                if($result['winner'] == $result['IdOne']) {
                    $speler_1 = "<span style=\"color: green\">".$result['FullName']."</span>";
                    $speler_2 = "<span style=\"color: red\">".$result['FullName2']."</span>";
                } elseif ($result['winner'] == $result['IdTwo']) {
                    $speler_1 = "<span style=\"color: red\">".$result['FullName']."</span>";
                    $speler_2 = "<span style=\"color: green\">".$result['FullName2']."</span>";
                } else {
                    $speler_1 = $result['FullName'];
                    $speler_2 = $result['FullName2'];
                }

                $HTML .=    "<form method='post'>
                                <tr>
                                    <td>".$result['matchId']."</td>
                                    <td>".$result['wedstrijd']."</td>
                                    <td>".$result['ronde']."</td>
                                    <td>{$speler_1}</td>
                                    <td></td>
                                    <td>{$speler_2}</td>";
                                    if(!$disableButtons) {
                                    $HTML.= "<td>
                                                <select style=\"width: 100%\" name='author'>
                                                    <option value=".$result['IdOne'].">".$result['FullName']."</option>
                                                    <option value=".$result['IdTwo'].">".$result['FullName2']."</option>
                                                </select>
                                            </td>";
                                    }
                                    if(!$disableButtons) {
                                    $HTML.="<td> 
                                                <button name='winner' value=".$result['matchId'].">Winnaar</button>
                                            </td>";
                                    }
                $HTML .=        "</tr>
                            </form>";
            }
            $HTML .= "</table>
                     {$champTekst}";

            $sth = $conn->prepare("SELECT COUNT(*) as TotalAmountofRows FROM matches WHERE wedstrijd = :wedstrijdId AND ronde=:rondeId");
            $sth->execute(ARRAY(
                    ':wedstrijdId'=>$wedstrijdId,
                    ':rondeId'=>$rondeId
                )
            );
            $totalAmountOfRows = $sth->fetch(PDO::FETCH_ASSOC)['TotalAmountofRows'];

            $sth = $conn->prepare("SELECT COUNT(*) as NumberOfWinners FROM `matches` WHERE wedstrijd =:wedstrijdId AND winner IS NOT NULL AND ronde=:rondeId");
            $sth->execute(ARRAY(
                    ':wedstrijdId'=>$wedstrijdId,
                    ':rondeId'=>$rondeId
                )
            );

            $totalWinners = $sth->fetch(PDO::FETCH_ASSOC)['NumberOfWinners'];
            $newRonde = $rondeId + 1;
            if($totalAmountOfRows == $totalWinners && !$finale && !$disableButtons) {
                $HTML .=    "<form method='post'>
                                <button type='submit' name='startNextRound' value='yes'>Start de {$newRonde}e ronde</button>
                            </form>";
            } elseif ($tourneyWinner && !$disableButtons) {
                $HTML .=    "<form method='post'>
                                <button type='submit' name='endTournament' value={$tournamentWinner}>Sluit het toernooi af</button>
                            </form>";
            }

            echo $HTML;
        } else {
            $HTML .= "Invalid wedstrijdId. Provide a valid one please.";
            echo $HTML;
        }
    } else {
        $HTML .= "Get was not set, no wedstrijdId has been registered. Please provide one.";
        echo $HTML;
    }