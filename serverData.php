<?php

function serverData(){ //Zugriff auf die Datenbank
	
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "isizindalwazi";
	
	$serverData[0] =$servername;
	$serverData[1] =$username;
	$serverData[2] =$password;
	$serverData[3] =$dbname;
	return $serverData;
}

    function conFunc(){ //Verbindung zur Datenbank aufbauen
		$serverData = serverData(); //Laden der Zugriffsdaten
		$servername = $serverData[0];
		$username = $serverData[1];
		$password = $serverData[2];
		$dbname = $serverData[3];
		$conn = new mysqli($servername, $username, $password, $dbname); //Verbindungsaufbau
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		return $conn;
    }
	
	
	function cloFunc($conn){ //Schließt die Verbindung zur Datenbank
		$conn->close();
	}
	
	function initializeDB(){ //Erstellt evtl. eine neue Datenbank und greift auf diese zu
		$serverData = serverData();
		
		$servername = $serverData[0];
		$username = $serverData[1];
		$password = $serverData[2];
		$dbname = $serverData[3];
		
		$conn = new mysqli($servername, $username, $password);
		$sql = "SHOW DATABASES LIKE '$dbname'";
		$result = $conn->query($sql);
		$rowcount=mysqli_num_rows($result);
		if(!$rowcount){ //Wenn noch keine passende Datenbank vorhanden
			$sql="CREATE DATABASE `$dbname`"; //Erstelle eine neue
			$result = $conn->query($sql); 
			cloFunc($conn);
			$con = conFunc();
			//erstelle die nötigen Tabellen
			createLogin($con);
			createInstitution($con);
			createInstimem($con);
			createCodes($con);
			
			//erstelle Testdaten
			createTestData($con);
			cloFunc($con);
		}
	}
	
	function createHistorie($con, $name){
		$tabName = str_replace("@","at",$name);
		
		$sql=	"CREATE TABLE `$tabName` (
				`meetingId` int(11) NOT NULL AUTO_INCREMENT,
				`metId` int(11) NOT NULL,
				`metDate` date NOT NULL,
				PRIMARY KEY (`meetingId`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		$result = $con->query($sql);
		}
		
		function createCodes($con){
		$sql=	"CREATE TABLE `codes` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`eMail` varchar(50) NOT NULL,
				`delDate` date NOT NULL,
				`posTest` tinyint(1) NOT NULL,
				`disInstitution` int(11) NOT NULL,
				PRIMARY KEY (`id`),
				KEY `CMail` (`eMail`),
				CONSTRAINT `CMail` FOREIGN KEY (`eMail`) REFERENCES `login` (`eMail`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		$result = $con->query($sql);
		}
			
		function createInstimem($con){
		$sql=	"CREATE TABLE `instimem` (
				`eMail` varchar(50) NOT NULL,
				`institutionID` int(11) NOT NULL,
				`password` varchar(150) NOT NULL,
				PRIMARY KEY (`eMail`),
				KEY `CInstitutionID` (`institutionID`),
				CONSTRAINT `CInstitutionID` FOREIGN KEY (`institutionID`) REFERENCES `institution` (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		$result = $con->query($sql);
		}
		
		function createInstitution($con){
		$sql=	"CREATE TABLE `institution` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(75) NOT NULL,
				`street` varchar(75) NOT NULL,
				`number` varchar(5) NOT NULL,
				`postcode` varchar(10) NOT NULL,
				`place` varchar(50) NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		$result = $con->query($sql);
		
		}

		function createLogin($con){
		$sql=	"CREATE TABLE `login` (
				`eMail` varchar(50) NOT NULL,
				`password` varchar(150) NOT NULL,
				`warningLevel` int(11) NOT NULL,
				PRIMARY KEY (`eMail`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
		$result = $con->query($sql);
		}
		
		function createTestData($con){ //Estellt einige Testdaten
			
				$usernames[0]="alleMeineEntchen@UrlaubAmSued.see";
				$usernames[1]="SuperHans@mailserver.net";
				$usernames[2]="Heinz@Ketchup.schmeckt";
				$usernames[3]="Carlos@ccz.net";
				$usernames[4]="Dieter@suedsee.com";
				$usernames[5]="Maxim@or.g";
				$usernames[6]="Horst@MyMa.il";
				$usernames[7]="Rüdiger@MyMa.il";
				$usernames[8]="Jürgen@Arbeitsmail.de";
				$usernames[9]="Sebastian@nichtArbeitsmail.info";
				$usernames[10]="Kevin@wasistdas.ups";
				$usernames[11]="Sabrina@allesmagisch.org";
				$usernames[12]="Alexandra@buchladen.de";
				$usernames[13]="Jaqueline@@berlin.info";
				$usernames[14]="Victoria@plaza.ru";
				$usernames[15]="Hanna@IchMagZue.ge";
				$usernames[16]="Sophie@Eis.de";
				$usernames[17]="Lisa@nochnmailser.ver";
				$usernames[18]="Isabelle@hatwaszutun.de";
				$usernames[19]="Katharina@mag.net";
				$usernames[20]="JochensTochter@malwaskreativ.es";
				
				$passwords[0]=sha1("IBelieveICanFly");
				$passwords[1]=sha1("SuperHansi92");
				$passwords[2]=sha1("Ketchup<3");
				$passwords[3]=sha1("Carlossos71");
				$passwords[4]=sha1("T4632.9Erz!");
				$passwords[5]=sha1("BisZurUnendlichkeit...");
				$passwords[6]=sha1("Rüdiger");
				$passwords[7]=sha1("HorstiBorsti");
				$passwords[8]=sha1("Aerosol145");
				$passwords[9]=sha1("L331!!!");
				$passwords[10]=sha1("Passwor123");
				$passwords[11]=sha1("**Simsalabim**");
				$passwords[12]=sha1("zH4werF5*");
				$passwords[13]=sha1("1234567");
				$passwords[14]=sha1("!ursup*285");
				$passwords[15]=sha1("LagerregaL");
				$passwords[16]=sha1("LoremIpsumDolor");
				$passwords[17]=sha1("Allergie1991$$");
				$passwords[18]=sha1("7896321*/-");
				$passwords[19]=sha1("___*-*___");
				$passwords[20]=sha1("<3<3<3");
				
				$institution[0]="";
				$institution[1]="n Arzt";
				$institution[2]="nochn Arzt";
				$institution[3]="besonderer Arzt";
				$institution[4]="spezieller Arzt";
				$institution[5]="ist das n Arzt?";
				$institution[6]="maln Amt";
				$institution[7]="nochmaln Amt";
				$institution[8]="Amt mit arbeitswilligen Leuten";
				$institution[9]="leeres Amt";
				$institution[10]="Amtsarzt";
				
				$ort[0]="";
				$ort[1]="so´n Kaff";
				$ort[2]="Frankfurt";
				$ort[3]="Buxthehude";
				$ort[4]="Berlin";
				$ort[5]="München";
				$ort[6]="Schimmeln";
				$ort[7]="Erfurt";
				$ort[8]="Mosbach";
				$ort[9]="Erbsen";
				$ort[10]="Bergen";
				
				$plz[0]="";
				$plz[1]="04856";
				$plz[2]="99584";
				$plz[3]="74853";
				$plz[4]="23841";
				$plz[5]="05894";
				$plz[6]="46853";
				$plz[7]="06431";
				$plz[8]="74821";
				$plz[9]="67842";
				$plz[10]="55324";
				
				$nr[0]="";
				$nr[1]="12";
				$nr[2]="17b";
				$nr[3]="23s";
				$nr[4]="12z";
				$nr[5]="227";
				$nr[6]="7";
				$nr[7]="5";
				$nr[8]="3";
				$nr[9]="2";
				$nr[10]="42";
				
				$strasse[0]="";
				$strasse[1]="die eine da";
				$strasse[2]="an so´nem Platz";
				$strasse[3]="drüben";
				$strasse[4]="Alexanderplatz";
				$strasse[5]="nebem Brauhaus";
				$strasse[6]="Hauptstraße";
				$strasse[7]="Krämerbrücke";
				$strasse[8]="Lohrtalweg";
				$strasse[9]="Erbsenzählerstraße";
				$strasse[10]="aufm Hügel";
				
				
				
				
				$datum = date('y.m.d');
			
			for($i = 0; $i < 21; $i++){
				$sql = "INSERT INTO `login`(`eMail`, `password`, `warningLevel`) VALUES ('$usernames[$i]','$passwords[$i]', '0')";
				$result=$con->query($sql);
				createHistorie($con,$usernames[$i]);
			}
			
			for($i = 0; $i < 11; $i++){ 
				$sql = "INSERT INTO `institution`(`name`, `street`, `number`, `postcode`, `place`) VALUES ('$institution[$i]','$strasse[$i]','$nr[$i]','$plz[$i]','$ort[$i]')";
				$result=$con->query($sql);
			}
			
			for($i = 0; $i < 21; $i++){
				$j = $i % 11;
				$sql = "SELECT id FROM `institution` WHERE name='$institution[$j]'";
				$result=$con->query($sql);
				foreach($result as $value)
					{
						$sql = "INSERT INTO `instimem`(`eMail`, `institutionID`, `password`) VALUES ('$usernames[$i]', '$value[id]','$passwords[$i]')";
					}
				$result=$con->query($sql);
			}
			
			for($i = 0; $i < 21; $i++){
					$posTest= $i%5;
					$k = $i % 11;
					$delDate = $datum;
				for($j = 0; $j < 14; $j++){
					$delDate++;
					$sql = "SELECT id FROM `institution` WHERE name='$institution[$k]'";
					$result=$con->query($sql);
					foreach($result as $value)
					{
						$disInstituiton =$value["id"];
					}
					if(!$posTest && $j > 11){
						$sql =  "INSERT INTO `codes`(`eMail`, `delDate`, `posTest`, `disInstitution`) VALUES ('$usernames[$i]', '$delDate','1', '$disInstituiton')";
					} else{
						$sql =  "INSERT INTO `codes`(`eMail`, `delDate`) VALUES ('$usernames[$i]', '$delDate')";
					}
					$result=$con->query($sql);
				}
			}
			
			
			for($i = 0; $i < 21; $i++){
				$tabName = str_replace("@","at",$usernames[$i]);
				$metDate = date('y.m.d', strtotime('-13 days'));
				$metid = 0;
				$z = rand(5,25);
				$mod2 = $z%5;
					$a=0;
				for($j = 0; $j < $z; $j++){
					$mod =rand(0,2);
					if($a<14){
					$a++;
					}
					if(!$mod){
						$b= 14-$a;
						$metDate = date('y.m.d', strtotime("-$b days"));
					}
					$delDate = $metDate;	
					for($y = 0; $y <14; $y++){
						$delDate++;
					}
					$x = rand(1,20);
					$metMail = $usernames[$x];
					$sql = "SELECT id FROM `codes` WHERE eMail='$metMail' AND delDate='$delDate'";
					$result=$con->query($sql);
					foreach($result as $value)
						{	
							$metid = $value["id"];
						}
					
					$sql = "INSERT INTO `$tabName`(`metId`, `metDate`) VALUES ('$metid','$metDate')";
					$result=$con->query($sql);
					$tabName2 = str_replace("@","at",$metMail);
					$sql = "SELECT id FROM `codes` WHERE eMail='$usernames[$x]' AND delDate='$delDate'";
					$result=$con->query($sql);
					foreach($result as $value)
						{	
							$metid2 = $value["id"];
						}
					$sql = "INSERT INTO `$tabName2`(`metId`, `metDate`) VALUES ('$metid2','$metDate')";
					$result=$con->query($sql);
					
				}
			}
			
			for($i = 0; $i < 21; $i++){
				$wc=0;
				$tabName = str_replace("@","at",$usernames[$i]);
				$sql = "SELECT * FROM $tabName t INNER JOIN codes c ON t.metId = c.id WHERE posTest = 1";
				$result=$con->query($sql);
				if($result){
					foreach($result as $value){
						$wc++;
					}
				}
				$sql = "UPDATE `login` SET `warningLevel`=$wc WHERE `eMail`='$usernames[$i]'";
				$result=$con->query($sql);
			}
		}
	?>