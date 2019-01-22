<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $username = 'root';
    $pass = '';
    $conn = new PDO("mysql:host=localhost;dbname=spelshit",$username, $pass);

    $HTML = "<nav>
                <a href='index.php'>Index</a>
                <a href='matches.php'>Matches</a>
                <a href='wedstrijden.php'>Wedstrijden</a>
            </nav>";

    if(count($_POST) > 0 && array_key_exists('save', $_POST)) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $birthdate = $_POST['birthdate'];
        $sth = $conn->prepare("INSERT INTO authors (first_name, last_name, email, birthdate, added) VALUES (:first_name, :last_name, :email, :birthdate, NOW())");
        $sth->execute(ARRAY(
                ':first_name'=>$first_name,
                ':last_name'=>$last_name,
                ':email'=>$email,
                ':birthdate'=>$birthdate,
            )
        );

        header('Location: user.php');
    }

    $HTML .= "<form method='post'>
                <table>
                    <tr>
                        <td>
                            First Name: 
                        </td>
                        <td>
                            <input type='text' name='first_name' placeholder='First Name' />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Last Name: 
                        </td>
                        <td>
                            <input type='text' name='last_name' placeholder='Last Name' />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Email: 
                        </td>
                        <td>
                            <input type='text' name='email' placeholder='Email' />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Birthdate: 
                        </td>
                        <td>
                            <input type='date' name='birthdate' />
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <button type='submit' name='save'>Add user</button>
                        </td>
                    </tr>
                </table>
            </form>";
    echo $HTML;