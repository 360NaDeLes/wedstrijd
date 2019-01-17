<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $username = 'root';
    $pass = '';
    $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);

    // Basically a huge query which joins two tables
    $sth = $conn->prepare("SELECT matches.matchId, a1.id as IdOne, CONCAT(a1.first_name, \" \", a1.last_name) as FullName, a2.id as IdTwo, CONCAT(a2.first_name, \" \", a2.last_name) AS FullName2 FROM `matches` INNER JOIN authors a1 on a1.id = matches.author_1
    INNER JOIN authors a2 on a2.id = matches.author_2");
    $sth->execute();
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);

    $HTML = "<table style=\"text-align: center\">
                <th>MatchId</th>
                <th>Author 1</th>
                <th>VS</th>
                <th>Author 2</th>";
    foreach($results as $result) {
        $HTML .=    "<tr>
                        <td>".$result['matchId']."</td>
                        <td>".$result['FullName']."</td>
                        <td><b>TEGEN</b></td>
                        <td>".$result['FullName2']."</td>
                    </tr>";
    }
    $HTML .= "</table>";

    echo $HTML;