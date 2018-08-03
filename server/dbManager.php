<?php

	class DBManager {

		private $connection = null;
		static protected $instance = null;

		function __construct(){
			if ($this->connection === null){
				$data = Debug::db();
				$this->connection = new PDO("mysql:host=localhost;dbname=edh",$data[0],$data[1]);
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			}
		}
		
		static public function app(){
	        if(self::$instance === null OR !is_a(self::$instance, "DBManager")) {
	            self::$instance = new DBManager();
	        }
	        return self::$instance;
		}

		public function doPurge($filename){

			$sql = "";	
			$tables = array();

			foreach ($this->query("show tables") as $result){
				$tables[] = $result["Tables_in_spacecombat"];
			}

			for ($i = 0; $i < sizeof($tables); $i++){
				$sql = "drop table ".$tables[$i];

				$stmt = $this->connection->prepare($sql);
				$stmt->execute();
				if ($stmt->errorCode() == 0){
					$sql = "";
					//echo "<div>dropping: ".$tables[$i]."</div>";
				} else continue;
			}

			$dump = file($filename);
			$sql = "";		

			foreach ($dump as $line){
				$startWith = substr(trim($line), 0 ,2);
				$endWith = substr(trim($line), -1 ,1);
				
				if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//' || $startWith == 'SE'){continue;}
					
				$sql = $sql.$line;
				if ($endWith == ';'){
					$stmt = $this->connection->prepare($sql);
					$stmt->execute();
					if ($stmt->errorCode() == 0){$sql = "";}
					else die("<div>Problem in executing the SQL query".$sql."</div>");
				}
			}
			echo "<div>SQL file imported successfully.</div>";


		}

		public function getLastInsertId(){
			return $this->connection->lastInsertId();
		}

		public function query($sql){
			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function update($sql){
			$stmt = $this->connection->prepare($sql);
			$stmt->execute();

			return $stmt->rowCount();
		}

		public function delete($sql){
			
			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			if ($stmt->errorCode() == 0){
				return true;
			}
		}



		public function insertSeek($name, $email, $plz, $ort, $msg, $pass){
			$stmt = $this->connection->prepare(
				"SELECT * FROM users WHERE name = :name"
			);

			$stmt->bindParam(":name", $name);
			
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			//echo "</br>ding</br></br>";
			//echo $result;
			//return;


			if (!$result){
				$stmt = $this->connection->prepare("
					INSERT INTO users
						(id, access, name, pass, email, plz, ort, time, title, msg)
					VALUES
						(:id, :access, :name, :pass, :email, :plz, :ort, :time, :title, :msg)
				");

				$access = 0;
				$time = 100;
				$title = "title";
				
				$stmt->bindParam(":id", $id);
				$stmt->bindParam(":access", $access);
				$stmt->bindParam(":name", $name);
				$stmt->bindParam(":pass", $pass);
				$stmt->bindParam(":email", $email);
				$stmt->bindParam(":plz", $plz);
				$stmt->bindParam(":ort", $ort);
				$stmt->bindParam(":time", $time);
				$stmt->bindParam(":title", $title);
				$stmt->bindParam(":msg", $msg);
				
				$stmt->execute();
				if ($stmt->errorCode() == 0){
					echo "<script>alert('Account created, please login');</script>";
				}
			}
		}


		public function doRegister($name, $pass){
			
			$sql = "SELECT * FROM users";
			$result = $this->query($sql);
			
			$valid = true;
			
			if ($result){
				foreach ($result as $entry){
					if ($entry["username"] == $name){
						$valid = false;
						break;
					}
				}
			}
			
			if ($valid){
				$stmt = $this->connection->prepare("
					INSERT INTO users
						(username, password, access)
					VALUES
						(:username, :password, :access)
				");

				$access = 0;
				
				$stmt->bindParam(":username", $name);
				$stmt->bindParam(":password", $pass);
				$stmt->bindParam(":access", $access);
				
				$stmt->execute();
				if ($stmt->errorCode() == 0){
					echo "<script>alert('Account created, please login');</script>";
				}
			}
			else { 
				echo "Account already exists !";
			}
		}
		
		public function validateLogin($name, $pass){
			//Debug::log("validating login");
			$stmt = $this->connection->prepare("
				SELECT id, access FROM users
				WHERE username = :username
				AND	password = :password
			");
			
			$stmt->bindParam(":username", $name);
			$stmt->bindParam(":password", $pass);
			$stmt->execute();
					
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if ($result){
				return $result;
			}
			else {
				return false;
			}	
		}

	}		

	?>
