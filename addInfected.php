<?php
    session_start();
    if(!isset($_SESSION['email']) && $_SESSION['type'] != 'employee' || 
        isset($_POST['action']) && $_POST['action'] == "Ausloggen"){
        session_destroy();
        header('Location: loginEmployee.php');
    }
    include 'serverData.php';
    $conn = conFunc();

    if(isset($_POST['action']) && $_POST['action'] == "Speichern"){
        $email = $_POST['email'];
        if(isset($_POST['posTest'])) $test = 1;
        else $test = 0;
        $testDate = $_POST['testDate'];
        
        $result = $conn->query("SELECT * FROM codes WHERE email = '$email' AND delDate = DATE_ADD('$testDate', INTERVAL 14 DAY);");
        if($result->num_rows == 0){
            $conn->query("INSERT INTO codes (eMail, delDate, posTest, disInstitution) VALUES ('$email', DATE_ADD('$testDate', INTERVAL 14 DAY), $test, 1 )");
        } else {
            $conn->query("UPDATE codes SET posTest = $test WHERE email = '$email' AND delDate = DATE_ADD('$testDate', INTERVAL 14 DAY);");
        }
        $conn->query("UPDATE ");
    }

    $result = $conn->query("SELECT eMail FROM codes GROUP BY eMail");

?>

<html>
    <head>
        <title>Enter Infected User</title>
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <h1>Add Infected</h1>
        <table>
            <tr>
                <th>User</th>
                <th>Positiv-Getestet</th>
                <th>Datum des Tests</th>
                <th>Speichern</th>
            </tr>
            <?php
                foreach($result as $row){
                    $email = $row['eMail'];
                    $posTest = false;
                    $date = "";
                    $resultCodes = $conn->query("SELECT posTest, DATE_SUB(delDate, INTERVAL 14 DAY) AS testDate FROM codes WHERE eMail = '$email';");
                    foreach($resultCodes as $rC){
                        if($rC['posTest'] == true){
                            $posTest = true;
                            $date = $rC['testDate'];
                            break;
                        }
                    }
                    echo "<tr>
                        <form action=\"addInfected.php\" method=\"POST\">
                            <td><input type=\"text\" name=\"email\" value=\"$email\"></td>
                            <td><input type=\"checkbox\" name=\"posTest\"";
                        if($posTest) echo " checked";
                        echo "></td>
                            <td><input type=\"date\" name=\"testDate\" value=\"$date\"></td>
                            <td><input type=\"submit\" name=\"action\" value=\"Speichern\"></td>
                        </form>
                    </tr>";
                }
            ?>
        </table>
        <form action="addInfected.php" method="POST">
            <input type="submit" name="action" value="Ausloggen">
        </form>
    </body>
</html>