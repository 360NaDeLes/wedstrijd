<?php 
    // All the class functions will have a small description in the the class files themselves.
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Including the 3 main class files
    include_once "clsWinners.php";
    include_once "clsMatches.php";
    include_once "clsAuthors.php";

    // Instances of the 3 classes
    $clsWinners = new winners();
    $clsMatches = new matches();
    $clsAuthors = new authors();

    $HTML = "<nav>
                <a href='index.php'>Index</a>
                <a href='matches.php'>Matches</a>
                <a href='wedstrijden.php'>Wedstrijden</a>
                <a href='user.php'>User</a>
            </nav>";

    if(array_key_exists('wedstrijd', $_GET) && array_key_exists('ronde', $_GET) && isset($_GET)) {
        // Decleration of base variables
        $wedstrijdId = $_GET['wedstrijd'];
        $rondeId = $_GET['ronde'];
        $finale = false;
        $champion = null;
        $tourneyWinner = "";
        $disableButtons = false;
        $finaleTekst = "";
        $champTekst = "";

        $winnersByTournId = $clsWinners->getWinnerByTournamentId($wedstrijdId);
        if($winnersByTournId['WinnersAmount'] == 1) {
            // If we're in here it means that we have a winner in the database and that all the buttons should be disabled
            $disableButtons = true;
            $champion = true;
        }

        $checkWinnersPerRoundId = $clsMatches->checkWinnersPerRoundId($wedstrijdId, $rondeId);
        if(count($checkWinnersPerRoundId) == 1) {
            // If we're in here it means that we only have 1 match in this round meaning that it's the finale round
            $finale = true;
            if(is_numeric($checkWinnersPerRoundId[0]['winner'])) {
                // If the value is numeric (not NULL) we have a champion and some variables get made for later
                $tourneyWinner = true;
                $tournamentWinner = $checkWinnersPerRoundId[0]['winner'];
            }
        } 
        if(isset($_POST) && count($_POST) > 0 && array_key_exists('winner', $_POST)) {
            // Update matches
            $winner = $_POST['author'];
            $matchid = $_POST['winner'];
            $updateMatches = $clsMatches->updateMatches($winner, $matchid, $wedstrijdId, $rondeId);
        } elseif(isset($_POST) && count($_POST) > 0 && array_key_exists('startNextRound', $_POST) && isset($_GET['ronde'])) {
            // We want to start the next round of matches
            $getRandomAuthorsForNextRound = $clsAuthors->getRandomAuthorsForNextRound($rondeId, $wedstrijdId);

            $limit = count($getRandomAuthorsForNextRound);
            $endForeach = $limit - 1;
            $newRonde = $_GET['ronde'] + 1;
            $ii = 0;

            foreach($getRandomAuthorsForNextRound as $authors) {
                // Insert the new fixtures
                $author1 = $getRandomAuthorsForNextRound[$ii]['winner'];
                $author2 = $getRandomAuthorsForNextRound[$ii + 1]['winner'];
                $insertIntoMatches = $clsMatches->insertIntoMatches($wedstrijdId, $newRonde, $author1, $author2);
                $ii = $ii + 2;
                if($ii > $endForeach) {
                    // End the foreach because we don't need a million errors
                    break;
                }
            }
            header("Location: matches.php?wedstrijd=".$wedstrijdId."&ronde=".$newRonde);
        } elseif(isset($_POST) && count($_POST) > 0 && array_key_exists('endTournament', $_POST)) {
            // End the tournament (all rounds have been played and also have winners)
            $wedstrijdId = $_GET['wedstrijd'];
            $rondeId = $_GET['ronde'];
            $winner = $_POST['endTournament'];
            $insertIntoUsers = $clsWinners->insertIntoWinners($wedstrijdId, $winner);
            header("Location: matches.php?wedstrijd={$wedstrijdId}&ronde={$rondeId}");
        }

        // Create the round links
        $selectDistinctMatches = $clsMatches->selectDistinctMatches($wedstrijdId);
        $RONDE = "";
        foreach($selectDistinctMatches as $ronde) {
            foreach($ronde as $key=>$value) {
                $RONDE .= "<a href=\"matches.php?wedstrijd=$wedstrijdId&ronde=$value\">Ronde: $value</a><br />";
            }
        }

        $getUsersByRound = $clsMatches->getUsersByRound($wedstrijdId, $rondeId);
        if(count($getUsersByRound) > 0) {
            if($finale) {
                $finaleTekst = "<b>FINALE RONDE</b>";
            }

            if($champion != null) {
                $getWinnerNameByTournamentId = $clsWinners->getWinnerNameByTournamentId($wedstrijdId);
                // I did the bottom declaration for the sake of consistancy
                $champName = $getWinnerNameByTournamentId;
                $champTekst = "<b>{$champName} heeft het toernooi gewonnen</b>";
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
            foreach($getUsersByRound as $userInfo) {
                // Determine user 1 or user 2 won the match, the winner gets a green name the loser gets a red one
                if($userInfo['winner'] == $userInfo['IdOne']) {
                    $speler_1 = "<span style=\"color: green\">".$userInfo['FullName']."</span>";
                    $speler_2 = "<span style=\"color: red\">".$userInfo['FullName2']."</span>";
                } elseif ($userInfo['winner'] == $userInfo['IdTwo']) {
                    $speler_1 = "<span style=\"color: red\">".$userInfo['FullName']."</span>";
                    $speler_2 = "<span style=\"color: green\">".$userInfo['FullName2']."</span>";
                } else {
                    $speler_1 = $userInfo['FullName'];
                    $speler_2 = $userInfo['FullName2'];
                }

                $HTML .=    "<form method='post'>
                                <tr>
                                    <td>".$userInfo['matchId']."</td>
                                    <td>".$userInfo['wedstrijd']."</td>
                                    <td>".$userInfo['ronde']."</td>
                                    <td>{$speler_1}</td>
                                    <td></td>
                                    <td>{$speler_2}</td>";
                                    if(!$disableButtons) {
                                    $HTML.= "<td>
                                                <select style=\"width: 100%\" name='author'>
                                                    <option value=".$userInfo['IdOne'].">".$userInfo['FullName']."</option>
                                                    <option value=".$userInfo['IdTwo'].">".$userInfo['FullName2']."</option>
                                                </select>
                                            </td>";
                                    }
                                    if(!$disableButtons) {
                                    $HTML.="<td> 
                                                <button name='winner' value=".$userInfo['matchId'].">Winnaar</button>
                                            </td>";
                                    }
                $HTML .=        "</tr>
                            </form>";
            }
            $HTML .= "</table>
                     {$champTekst}";

            $getTotalAmountOfMatchesPerRound = $clsMatches->getTotalAmountOfMatchesPerRound($wedstrijdId, $rondeId);

            $getTotalAmountOfWinnersPerRound = $clsMatches->getTotalAmountOfWinnersPerRound($wedstrijdId, $rondeId);

            $newRonde = $rondeId + 1;
            // If the total rounds and the total winneres per rounds are equal it means that all matches have a winner
            if($getTotalAmountOfMatchesPerRound == $getTotalAmountOfWinnersPerRound && !$finale && !$disableButtons) {
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