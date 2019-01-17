<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $username = 'root';
    $pass = '';
    $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);

    // No longer needed since I created a proper view for each game
    /*
    if(!array_key_exists('matches', $_GET)) {
        // Empty the table, for testing sake.
        $sth = $conn->prepare("TRUNCATE TABLE matches");
        $sth->execute();
    }
    */

    $POSTIsSet = false;
    $limit = 32; // Standard 32, though this value should never stay the same (either it will be changed to 32 again or to 16)
    if(isset($_POST) && count($_POST) > 1 || array_key_exists('amount', $_POST)) {
        $POSTIsSet = true;
        $limit = $_POST['amount'] * 2;
        $sth = $conn->prepare("SELECT id FROM authors ORDER BY RAND() LIMIT :limit");
        $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
        $sth->execute();

        // We relocate because we need to clear the POST array, this is a sure way to do it
        //header('Location: index.php?matches=yes');
    } else {
        $sth = $conn->prepare("SELECT id FROM authors ORDER BY RAND()");
        $sth->execute();
    }

    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    
    echo    "<nav>
                <a href='index.php'>Index</a>
                <a href='matches.php'>Matches</a>
                <a href='wedstrijden.php'>Wedstrijden</a>
                <a href='user.php'>User</a>
            </nav>
            <form method='post'>
                <select name='amount'>
                    <option value='16'>16</option>
                    <option value='8'>8</option>
                </select>
                <button type='submit' name='testShit' value='yeet'>Test</button>
            </form>";

    $endForeach = $limit - 1;

    if($POSTIsSet) {
        // Loop insert and array related stuff.
        $sth = $conn->prepare("SELECT IFNULL(MAX(wedstrijd) + 1, 1) as NewMaxNumber FROM matches");
        $sth->execute();
        $maxWedstrijd = $sth->fetch(PDO::FETCH_ASSOC);
        print_r($maxWedstrijd);
        $ii = 0;
        foreach($result as $authors) {
            $sth = $conn->prepare("INSERT INTO matches (wedstrijd, ronde, author_1, author_2) VALUES (:wedstrijd, 1, :author1, :author2)");
            $sth->execute(ARRAY(
                    ':wedstrijd'=>$maxWedstrijd['NewMaxNumber'],
                    ':author1'=> $result[$ii]['id'],
                    ':author2'=> $result[$ii + 1]['id']
                )
            );
            $ii = $ii + 2;
            if($ii > $endForeach) {
                // End the foreach because we don't need a million errors
                break;
            }
        }
    }