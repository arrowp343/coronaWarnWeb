<?php
    session_start();
    if(!isset($_SESSION['email']) && $_SESSION['type'] != 'employee' || 
        isset($_POST['action']) && $_POST['action'] == "Ausloggen"){
        session_destroy();
        header('Location: loginEmployee.php');
    }
    include 'serverData.php';
    $conn = conFunc();

    if(isset($_POST['action']) && $_POST['action'] == "Eintragen"){
        $id = $_POST['id'];
        if($_POST['test'] == 'positive')
            $test = 1;
        else 
            $test = 0;
        
        //setze auf codes auf positiv getestet
        $conn->query("UPDATE codes SET posTest = $test WHERE email = (SELECT email FROM codes WHERE id = '$id');");
        
        //erhöhe warningLevel
        $emailResult = $conn->query("SELECT email FROM codes WHERE id = '$id'");
        foreach($emailResult as $r) $email = strtolower(str_replace("@", "at", $r['email']));
        
        if($test) $deltaWarningLevel = 1;
            else $deltaWarningLevel = -1;

        $contacts = $conn->query("SELECT metId FROM `$email`;");
        foreach($contacts as $c){
            $id = $c['metId'];
            
            $resultEmail = $conn->query("SELECT email FROM codes WHERE id = $id;");
            foreach($resultEmail as $r) $contactEmail = $r['email'];

            $warningLevelResult = $conn->query("SELECT warningLevel FROM login WHERE email = '$contactEmail'");
            foreach($warningLevelResult as $r) $warningLevel = $r['warningLevel'];
            $warningLevel += $deltaWarningLevel;
            $conn->query("UPDATE login SET warningLevel = $warningLevel WHERE email = '$contactEmail'");
        }
    }
?>

<html>
    <head>
        <title>Enter Infected User</title>
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <h1>Add Infected</h1>
        <form action="addInfected.php" method="POST">
            <table>
                <tr>
                    <td>
                        Id der Person
                    </td>
                    <td>
                        <div>
                            <input type="number" name="id" placeholder="Id">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Testergebnis
                    </td>
                    <td>
                        <div>
                            <input id="radioPositive" type="radio" name="test" value="positive"><label for="radioPositive">Positiv</label>
                        </div>
                        <div>
                            <input id="radioNegative" type="radio" name="test" value="negative"><label for="radioNegative" checked="checked">Negativ</label>
                        </div>
                    </td>
                </tr>
            </table>
            <div>
                <input type="submit" name="action" value="Eintragen">
            </div>
        </form>
        <form action="addInfected.php" method="POST">
            <input type="submit" name="action" value="Ausloggen">
        </form>
    </body>
</html>