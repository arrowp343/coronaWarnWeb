<?php
    if(isset($_GET['m']) && $_GET['m'] == "registrationSuccessful"){
        $message = "Registrierung war erfolgreich";
    }

    if(isset($_POST['email']) && isset($_POST['password'])){
        include 'serverData.php';

        $email = $_POST['email'];
        $password = $_POST['password'];
        $expect = 0;

        $conn = conFunc();
        $result = $conn->query("SELECT password FROM login WHERE eMail = '$email';");
        foreach($result as $value)
            $expect = $value['password'];
        
        if($expect){
            if(sha1($password) == $expect){
            session_start(['cookie_lifetime' => 3600]);
            $_SESSION['email'] = $email;
            $_SESSION['type'] = 'user';
            header('Location: history.php');
            }
            else {
                $message = "Email oder Passwort sind ungültig!";
            }
        }else {
            $message = "Email oder Passwort sind ungültig!";
        }
    }
?>


<html>
    <head>
        <title>Login - User</title>
        <meta charset="utf-8">
		<link href="style.css" rel="stylesheet">
    </head>
    <body>
        <div id="loginBoxWrapper">
            <div id="loginBox">
                <h1>Login - CoronaWarnWeb</h1>
                <form action="loginUser.php" method="POST">
                    <div>
                        <input type="text" placeholder="E-Mail-Adresse" name="email" required>
                    </div>
                    <div>
                        <input type="password" placeholder="Passwort" name="password" required>
                    </div>
                    <div>
                        <input type="submit" value="Einloggen">
                    </div>
                </form>
                <a href="loginEmployee.php"><button>Wechsel zu Mitarbeiter</button></a>
                <?php
                    if(isset($message)){
                ?>
                    <div>
                        <p><?php echo $message ?></p>
                    </div>
                <?php
                    }
                    else{
                ?>
                <div>
                    <p>Noch nicht registriert?</p>
                    <a href="registerUser.php"><button>Registrieren</button></a>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </body>
</html>