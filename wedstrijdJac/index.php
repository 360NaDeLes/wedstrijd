<?php
    // We need a session so we can store the added users
    session_start();

    if(array_key_exists('addedUsers', $_SESSION) && count($_SESSION['addedUsers']) < 1) {
        $_SESSION['addedUsers'] = array();
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $username = 'root';
    $pass = '';
    $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);

    // Including the 3 main class files
    include_once "clsWinners.php";
    include_once "clsMatches.php";
    include_once "clsAuthors.php";

    // Instances of the 3 classes
    $clsWinners = new winners();
    $clsMatches = new matches();
    $clsAuthors = new authors();

    $authors = $clsAuthors->getAllAuthors();

    $SELECT = "<select name=\"author\">";
    foreach($authors as $author) {
        $SELECT .= "<option value=".$author['id'].">".$author['FullName']."</option>";
    }
    $SELECT .= "</select>";

    $POSTIsSet = false;
    $limit = 32; // Standard 32, though this value should never stay the same (either it will be changed to 32 again or to 16)
    if(isset($_POST) && count($_POST) > 1) {
        $user = $_POST['author'];
        $notInArray = true;

        foreach($_SESSION['addedUsers'] as $key=>$value) {
            if($_SESSION['addedUsers'][$key] == $user) {
                $notInArray = false;
            }
        }
        if($notInArray) {
            array_push($_SESSION['addedUsers'], $user);
        } else {
            echo "User is already added";
        }
        echo"<pre>";print_r($_SESSION);echo"</pre>";
    }

    $addedUsers = "";
    if(array_key_exists('addedUsers', $_SESSION) && count($_SESSION['addedUsers']) > 0) {
        $addedUsers = "<table>";
        foreach($_SESSION['addedUsers'] as $key=>$value) {
            $authorInfo = $clsAuthors->getAuthorById($value);
            $addedUsers .=  "<tr>
                                <td>".$authorInfo['FullName']."</td>
                            </tr>";
        }
        $addedUsers .= "</table>";
    }
    
    echo    "<nav>
                <a href='index.php'>Index</a>
                <a href='matches.php'>Matches</a>
                <a href='wedstrijden.php'>Wedstrijden</a>
                <a href='user.php'>User</a>
            </nav>
            <form method='post'>
                $SELECT <br />
                $addedUsers <br />
                <button type='submit' name='addUser' value='yes'>Voeg gebruiker toe</button>
            </form>";

    $endForeach = $limit - 1;