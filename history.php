<?php
    session_start();
    if(!isset($_SESSION['email']) && $_SESSION['type'] != 'user' ||
        isset($_POST['action']) && $_POST['action'] == "Ausloggen"){
        header('Location: loginUser.php');
    }

    include 'serverData.php';
    $conn = conFunc();
    $email = $_SESSION['email'];
    $emailAt = str_replace("@","at", $email);
    $emailLowerCase = strtolower($email);
    $emailAtLowerCase = strtolower($emailAt);
    if(isset($_POST['number'])){
        $number = $_POST['number'];
        $date = $_POST['date'];

        $_sql = "INSERT INTO `$emailAtLowerCase`(metId, metDate) VALUES ('$number','$date')";
        $result = $conn->query($_sql);

        $delDate = $date;
         for($i=0; $i<14; $i++){
            $delDate++;
        }

        $_sql = "SELECT id FROM `codes` WHERE eMail='$email' AND delDate = '$delDate'";
        $result = $conn->query($_sql);

        $metId = 0;
        foreach($result as $value) {
            $metId = $value["id"];
       }
       $_sql = "SELECT email from `codes` WHERE id='$number'";
       $result = $conn->query($_sql);
       $tabName = "";
       foreach($result as $value) {
           $tabName = $value["email"];
      }
      $tabName2 = strtolower(str_replace("@","at", $tabName));
      $_sql = "INSERT INTO `$tabName2`(metId, metDate) VALUES ('$metId','$date')";
      $result = $conn->query($_sql);

      $test = 0;
      $result = $conn->query("SELECT posTest FROM codes WHERE id = $metId");
      foreach($result as $r) $test = $r['posTest'];
      if($test == 1){//wenn der eingeloggte positiv
        $result = $conn->query("SELECT warningLevel FROM login WHERE eMail = '$tabName'");
        foreach($result as $r) $warningLevel = $r['warningLevel'];
        $warningLevel++;
        $conn->query("UPDATE login SET warningLevel = $warningLevel WHERE eMail = '$tabName'");
      } 
      
      $result = $conn->query("SELECT posTest FROM codes WHERE id = $number");
      foreach($result as $r) $test = $r['posTest'];
      if($test == 1){//wenn der getroffene positiv
        $result = $conn->query("SELECT warningLevel FROM login WHERE eMail = '$email'");
        foreach($result as $r) $warningLevel = $r['warningLevel'];
        $warningLevel++;
        $conn->query("UPDATE login SET warningLevel = $warningLevel WHERE eMail = '$email'");
      }
    }
    $sql = "SELECT metId, metDate, posTest FROM `$emailAtLowerCase` e INNER JOIN codes ON e.metId = codes.id ORDER BY metDate, metId;";
    $result = $conn->query($sql);
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
                <th>Code der Kontaktperson</th>
                <th>Datum</th>
                <th>Positiv-Getestet</th>
            </tr>
            <?php
                if($result){
                    foreach($result as $row){
                        $metId = $row['metId'];
                        $metDate = $row['metDate'];
                        $posTest = $row['posTest'];
                        echo "<tr>
                            <td>$metId</td>
                            <td>$metDate</td>
                            <td>";
                            if($posTest){
                            echo "ja";
                            }
                            else{
                             echo "nein";
                            }
                            echo "</td></tr>";
                    }
                }
            ?>
        </table>
        <p id="warnLevel">Aktuelle Warnstufe:
        <?php echo $warning."<br><br>Empfehlung:";
        if($warning == 0) {
        echo "<br><br>Sie besitzen derzeit geringes Risiko. Sie hatten innerhalb der letzten 14 Tage keinen kontakt mit positiv gemeldeten Personen. <br>Halten Sie sich stets an die Corona Maßnahmen der Bundesregierung.<br>Siehe unter <a href='https://www.bundesregierung.de/breg-de/themen/coronavirus/corona-diese-regeln-und-einschraenkung-gelten-1734724' target='_blank'>Corona Maßnahmen</a>.";
         }
         else if($warning >= 1 && $warning <= 10){
         echo "<br><br>Sie besitzen derzeit ein hohes Risiko, da Sie innerhalb der letzten 14 Tage kontakt mit ".$warning." positiv gemeldeten Personen hatten.<br>Es ist zu empfehlen, sich testen zu lassen.<br>Siehe unter <a href='https://www.bundesregierung.de/breg-de/themen/coronavirus/corona-diese-regeln-und-einschraenkung-gelten-1734724'target='_blank'>Corona Maßnahmen</a>.";
         }
        else if($warning > 10 ){
        echo "<br><br>Sie besitzen derzeit ein sehr hohes Risiko, da Sie innerhalb der letzten 14 Tage kontakt mit ".$warning." positiv gemeldeten Personen hatten.<br><b> Lassen Sie sich bitte testen!</b><br>Siehe unter <a href='https://www.bundesregierung.de/breg-de/themen/coronavirus/corona-diese-regeln-und-einschraenkung-gelten-1734724' target='_blank'>Corona Maßnahmen</a>.";
        }

         ?></p>
        <form action="history.php" method="POST">
            <input type="submit" name="action" value="Ausloggen">
        </form>
    </body>
</html>