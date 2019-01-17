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

    if(isset($_POST) && count($_POST) > 0 && array_key_exists('winner', $_POST)) {
        $winner = $_POST['author'];
        $matchid = $_POST['winner'];
        $sth = $conn->prepare("UPDATE matches SET winner=:winner WHERE matchid=:matchid");
        $sth->execute(ARRAY(
                ':winner'=>$winner,
                ':matchid'=>$matchid
            )
        );
    } elseif(isset($_POST) && count($_POST) > 0 && array_key_exists('startRoundTwo', $_POST) && $_GET['ronde'] = 1) {
        // GET WINNERS, MAKE FIXTURES AND CHANGE URL TO ronde=2
        $wedstrijdId = $_GET['wedstrijd'];
        $rondeId = $_GET['ronde'];
        $sth = $conn->prepare("SELECT winner FROM `matches` WHERE wedstrijd =:wedstrijdId AND ronde =:rondeId AND winner IS NOT NULL");
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
    }

    if(array_key_exists('wedstrijd', $_GET) && array_key_exists('ronde', $_GET) && isset($_GET)) {
        $wedstrijdId = $_GET['wedstrijd'];
        $rondeId = $_GET['ronde'];
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
            $HTML.= "<table style=\"text-align: center\">
                        <th>MatchId</th>
                        <th>Wedstrijd</th>
                        <th>Ronde</th>
                        <th>Author 1</th>
                        <th>VS</th>
                        <th>Author 2</th>
                        <th colspan=2>Winner</th>";
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
                                    <td>$speler_1</td>
                                    <td></td>
                                    <td>$speler_2</td>
                                    <td>
                                        <select style=\"width: 100%\" name='author'>
                                            <option value=".$result['IdOne'].">".$result['FullName']."</option>
                                            <option value=".$result['IdTwo'].">".$result['FullName2']."</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button name='winner' value=".$result['matchId'].">Winnaar</button>
                                    </td>
                                </tr>
                            </form>";
            }
            $HTML .= "</table>";

            $sth = $conn->prepare("SELECT COUNT(*) as TotalAmountofRows FROM matches WHERE wedstrijd = :wedstrijdId");
            $sth->execute(ARRAY(
                    ':wedstrijdId'=>$wedstrijdId
                )
            );
            $totalAmountOfRows = $sth->fetch(PDO::FETCH_ASSOC)['TotalAmountofRows'];

            $sth = $conn->prepare("SELECT COUNT(*) as NumberOfWinners FROM `matches` WHERE wedstrijd =:wedstrijdId AND winner IS NOT NULL");
            $sth->execute(ARRAY(
                    ':wedstrijdId'=>$wedstrijdId
                )
            );

            $totalWinners = $sth->fetch(PDO::FETCH_ASSOC)['NumberOfWinners'];

            if($totalAmountOfRows == $totalWinners) {
                $HTML .=    "<form method='post'>
                                <button type='submit' name='startRoundTwo' value='yes'>Start de 2e ronde</button>
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