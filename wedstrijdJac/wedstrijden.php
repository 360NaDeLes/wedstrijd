<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $username = 'root';
    $pass = '';
    $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);

    $sth = $conn->prepare("SELECT DISTINCT wedstrijd FROM `matches`");
    $sth->execute();
    $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    
    $HTML = "<nav>
                <a href='index.php'>Index</a>
                <a href='matches.php'>Matches</a>
                <a href='wedstrijden.php'>Wedstrijden</a>
                <a href='user.php'>User</a>
            </nav>";

    foreach($results as $result) {
        foreach($result as $value) {
            $HTML .= "<a href=\"matches.php?wedstrijd=$value&ronde=1\">Wedstrijd $value</a><br />";
        }
    }
    echo $HTML;