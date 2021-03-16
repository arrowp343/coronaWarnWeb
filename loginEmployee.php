<?php
    if(isset($_POST['email']) && isset($_POST['password'])){
        include 'serverData.php';

        $email = $_POST['email'];
        $password = $_POST['password'];
        $expect = 0;

        $conn = conFunc();
        $result = $conn->query("SELECT password, institutionID FROM instimem WHERE eMail = '$email';");
        foreach($result as $value){
            $expect = $value['password'];
            $instiID = $value['institutionID'];
        }
        
        if($expect){
            if(sha1($password) == $expect){
                session_start(['cookie_lifetime' => 3600]);
                $_SESSION['email'] = $email;
                $_SESSION['instiID'] = $instiID;
                $_SESSION['type'] = 'employee';
                header('Location: addInfected.php');
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
        <title>Login - Employee</title>
        <meta charset="utf-8">
		<link href="style.css" rel="stylesheet">
    </head>
    <body>
        <div id="loginBoxWrapper">
            <div id="loginBox">
                <h1>Login - Employee</h1>
                <form action="loginEmployee.php" method="POST">
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
                <a href="loginUser.php"><button>Wechsel zu User</button></a>
                <?php
                    if(isset($message)){
                ?>
                    <div>
                        <p><?php echo $message ?></p>
                    </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </body>
</html>