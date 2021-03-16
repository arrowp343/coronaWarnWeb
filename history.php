<?php
    session_start();
    if(!isset($_SESSION['email']) && $_SESSION['type'] != 'user' ||
        isset($_POST['action']) && $_POST['action'] == "Ausloggen"){
        header('Location: loginUser.php');
    }

    include 'serverData.php';
    $conn = conFunc();
    $email = $_SESSION['email'];

    if(isset($_POST['number'])){
        $number = $_POST['number'];
        $date = $_POST['date'];
        $_sql = "INSERT INTO `$email`(metId, metDate) VALUES ('$number','$date')";
        $result = $conn->query($_sql);
    }

    $result = $conn->query("SELECT meetingId, metId, metDate, posTest FROM $email INNER JOIN codes ON $email.metId = codes.id;");
    $warningResult = $conn->query("SELECT warningLevel FROM login WHERE email = '$email';");
    foreach($warningResult as $w)
        $warning = $w['warningLevel'];
?>
<html>
    <head>
        <title>Corona Contact History</title>
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <h1>Corona Kontaktverlauf</h1>
        <p>Verlauf eintragen</p>
        <form action="history.php" method="POST">
            <div>
                <input type="number" placeholder="BegegnungsID" name="number" required>
            </div>
            <div>
                <input type="date" placeholder="Begegnungsdatum" name="date" required>
            </div>
            <div>
                <input type="submit" value="Eintragen">
            </div>
        </form>
    
        <table>
            <tr>
                <th>ID</th>
                <th>Code der Kontaktperson</th>
                <th>Datum</th>
                <th>Positiv-Getestet</th>
            </tr>
            <?php
                if($result){
                    foreach($result as $row){
                        $id = $row['meetingId'];
                        $metId = $row['metId'];
                        $metDate = $row['metDate'];
                        $posTest = $row['posTest'];
                        echo "<tr>
                            <td>$id</td>
                            <td>$metId</td>
                            <td>$metDate</td>
                            <td>$posTest</td>
                        </tr>";
                    }
                }
            ?>
        </table>
        <p id="warnLevel">Aktuelle Warnstufe:
        <?php echo $warning."<br><br>Empfehlung:";
        if($warning == 0) {
        echo "<br><br>Sie besitzen derzeit geringes Risiko. Sie haben innerhalb der letzten 14 Tage keinen kontakt mit positiv gemeldeten Personen. <br>Halten Sie stets and die Corona Maßnahmen der Bundesregierung.<br>Siehe unter <a href='https://www.bundesregierung.de/breg-de/themen/coronavirus/corona-diese-regeln-und-einschraenkung-gelten-1734724' target='_blank'>Corona Maßnahmen</a>.";
         }
         else if($warning >= 1 && $warning <= 10){
         echo "<br><br>Sie besitzen derzeit ein hohes Risiko, da Sie innerhalb der letzten 14 Tage kontakt mit ".$warning." positiv gemeldeten Personen.<br>Es ist zu empfehlen, sich testen zu lassen.<br>Siehe unter <a href='https://www.bundesregierung.de/breg-de/themen/coronavirus/corona-diese-regeln-und-einschraenkung-gelten-1734724'target='_blank'>Corona Maßnahmen</a>.";
         }
        else if($warning > 10 ){
        echo "<br><br>Sie besitzen derzeit ein sehr hohes Risiko, da Sie innerhalb der letzten 14 Tage kontakt mit ".$warning." positiv gemeldeten Personen.<br><b> Lassen Sie sich bitte testen!</b><br>Siehe unter <a href='https://www.bundesregierung.de/breg-de/themen/coronavirus/corona-diese-regeln-und-einschraenkung-gelten-1734724' target='_blank'>Corona Maßnahmen</a>.";
        }

         ?></p>
        <form action="history.php" method="POST">
            <input type="submit" name="action" value="Ausloggen">
        </form>
    </body>
</html>