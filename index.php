<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $username = 'root';
    $pass = '';
    $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);

    // Empty the table, for testing sake.
    $sth = $conn->prepare("TRUNCATE TABLE matches");
    $sth->execute();

    print_r($_POST);
    $POSTIsSet = false;
    if(isset($_POST) && count($_POST) > 1 || array_key_exists('amount', $_POST)) {
        $POSTIsSet = true;
        $limit = $_POST['amount'] * 2;
        $sth = $conn->prepare("SELECT id FROM authors ORDER BY RAND() LIMIT :limit");
        $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
        $sth->execute();
    } else {
        $sth = $conn->prepare("SELECT id FROM authors ORDER BY RAND()");
        $sth->execute();
    }

    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    print_r($result);

    echo    "<form method='post'>
                <select name='amount'>
                    <option value='16'>16</option>
                    <option value='8'>8</option>
                </select>
                <button type='submit' name='testShit' value='yeet'>Test</button>
            </form>";
    $endForeach = $limit - 1;
    if($POSTIsSet) {
        // Loop insert and array related stuff.
        $ii = 0;
        foreach($result as $authors) {
            $sth = $conn->prepare("INSERT INTO matches (author_1, author_2) VALUES (:author1, :author2)");
            $sth->execute(ARRAY(
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