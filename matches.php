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

    if(array_key_exists('wedstrijd', $_GET) && isset($_GET)) {
        $wedstrijdId = $_GET['wedstrijd'];
        // Basically a huge query which joins two tables
        $sth = $conn->prepare("SELECT matches.matchId, matches.wedstrijd, matches.ronde, a1.id as IdOne, CONCAT(a1.first_name, \" \", a1.last_name) as FullName, a2.id as IdTwo, CONCAT(a2.first_name, \" \", a2.last_name) AS FullName2 FROM `matches` INNER JOIN authors a1 on a1.id = matches.author_1
        INNER JOIN authors a2 on a2.id = matches.author_2 WHERE wedstrijd = :wedstrijdId");
        $sth->execute(ARRAY(
                ':wedstrijdId'=>$wedstrijdId
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
                $HTML .=    "<form method='post'>
                                <tr>
                                    <td>".$result['matchId']."</td>
                                    <td>".$result['wedstrijd']."</td>
                                    <td>".$result['ronde']."</td>
                                    <td>".$result['FullName']."</td>
                                    <td></td>
                                    <td>".$result['FullName2']."</td>
                                    <td>
                                        <select style=\"width: 100%\">
                                            <option value=".$result['IdOne'].">".$result['FullName']."</option>
                                            <option value=".$result['IdTwo'].">".$result['FullName2']."</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button name='winner'>Winnaar</button>
                                    </td>
                                </tr>
                            </form>";
            }
            $HTML .= "</table>";

            echo $HTML;
        } else {
            $HTML .= "Invalid wedstrijdId. Provide a valid one please.";
            echo $HTML;
        }
    } else {
        $HTML .= "Get was not set, no wedstrijdId has been registered. Please provide one.";
        echo $HTML;
    }