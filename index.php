<?php

	ini_set('display_errors', 1); 
	error_reporting(E_ALL);
	
	include(dirname(__FILE__) . "/autoload.php");
	session_start();
	
    Debug::open();
   // Debug::log("bug");

	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$name = $_POST["name"];
		$mail = $_POST["mail"];
		$plz = $_POST["plz"];
		$ort = $_POST["ort"];
		$comment = $_POST["comment"];
		$pass = $_POST["pass"];
		
		echo "name ".$name."</br>";
		echo "mail ".$mail."</br>";
		echo "plz ". $plz.", len: ".strlen($plz)."</br>";
		echo "ort ". $ort."</br>";
		echo "comment ".$comment."</br>";
		echo "pass ".$pass."</br></br></br>";
		
		$valid = 1;
		
		if (strlen($name) < 4){
			echo "Ungültiger Nutzername</br>";
			$valid = 0;
		}
		
		if (strlen($mail) < 4){
			echo "Ungültige Email-Adresse</br>";
			$valid = 0;
		}
		
		if (strlen($plz < 5) || $plz < 0 || $plz > 99999){
			echo "Ungültige PLZ</br>";
			$valid = 0;
		}
		
		if (strlen($ort) < 3){
			echo "Ungültige Ort</br>";
			$valid = 0;
		}
		
		if (strlen($pass) < 5){
			echo "Ungültige Passwort</br>";
			$valid = 0;
		}
		
		
		if (!$valid){
			echo "valid!";

			echo DBManager::app()->insertSeek($name, $mail, $plz, $ort, $comment, $pass);
		}
		
	
	
} else echo "no post";



?>

<!DOCTYPE html>
<html>
<head>
	<link rel='stylesheet' href='style.css'/>
	<script src="script.js"></script>
	<script src="jquery-2.1.1.min.js"></script>
</head>
	<body> 
		<div id="createDiv">
			<form method="post">
				<div class="divWrapper">
					<div>Benutzername</div>
					<div>
						<input type="text" placeholder="" name="name" value=""></input>
					</div>
				</div>
				<div class="divWrapper">
					<div>E-Mail (für Kontaktaufnahme)</div>
					<div>
						<input type="text" placeholder="" name="mail" value=""></input>
					</div>
				</div>
				<div class="divWrapper">
					<div>PLZ (für Suche)</div>
					<div>
						<input type="number" placeholder="" name="plz" value=""></input>
					</div>
				</div>
				<div class="divWrapper">
					<div>Ort (für Suche)</div>
					<div>
						<input type="text" placeholder="" name="ort" value=""></input>
					</div>
				</div> 
				<div class="commentWrapper">
					<div>Kurze Beschreibung</div>
					<div>
						<textarea name="comment" id="comment" ></textarea>
					</div>
				</div> 
				<div class="divWrapper">
					<div>Passwort (für Änderung/Löschung)</div>
					<div>
						<input type="password" placeholder="" name="pass" ></input>
					</div>
				</div>
				<div class="divWrapper">
					<input id="confirm" type="submit" value="Eintrag speichern"></input>
				</br>
			</form>
		</div>
	</body>
</html>

<script>
</script>