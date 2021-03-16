<?php
    $message = "";
    if(isset($_POST['email']) && isset($_POST['password']) && $_POST['password2']){
        if($_POST['password'] == $_POST['password2']){
            include 'serverData.php';

            $email = $_POST['email'];
            $password = sha1($_POST['password']);

            $conn = conFunc();

            if ($conn->query("INSERT INTO login (email, password, warningLevel) VALUES ('$email', '$password', 0);") === TRUE) {
                createHistorie($conn, $email);
                header('Location: loginUser.php?m=registrationSuccessful');
            } else if($conn->error == "Duplicate entry '$email' for key 'PRIMARY'") {
                $message = "E-Mail-Adresse bereits vergeben!";
            }
            else {
                $message =  "Error:<br>" . $conn->error;
            }
        }
        else{
            $message = "Passwörter stimmen nicht überein!";
        }
    }

?>

<html>
    <head>
        <title>Register - User</title>
        <meta charset="utf-8">
		<link href="style.css" rel="stylesheet">
    </head>
    <body>
        <div id="loginBoxWrapper">
            <div id="loginBox">
                <h1>Registrieren</h1>
                <form action="registerUser.php" method="POST">
                    <div>
                        <input type="text" placeholder="E-Mail-Adresse" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>"required>
                    </div>
                    <div>
                        <input type="password" placeholder="Passwort" name="password" required>
                        <input type="password" placeholder="Passwort wiederholen" name="password2" required>
                    </div>
                    <div>
                        <input type="submit" value="Registrieren">
                    </div>
                    <div>
                        <p class="messageBox">
                            <?php
                                echo $message;
                            ?>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>