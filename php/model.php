<?php
class model{
	#Partially implemented
	public static function connectDatabase(){
		#Determines if online (dev) or online
		#Change username, database, etc. according to particular server
		$dir = getcwd();
		$categories=LOCAL_ID;
		if(strpos($dir,$categories)){
			$database = MYSQL_DATABASE_LOCAL;
			$username = MYSQL_USERNAME_LOCAL;
			$password = MYSQL_PASSWORD_LOCAL;
			$host = MYSQL_HOST_LOCAL;
		}else{
			$database = MYSQL_DATABASE_ONLINE;
			$username = MYSQL_USERNAME_ONLINE;
			$password = MYSQL_PASSWORD_ONLINE;
			$host = MYSQL_HOST_ONLINE;
		}
		try {
			$pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			echo "Connected!";
			return $pdo;
		} catch (PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	}
	public static function pythonStringToPHPArray($input){
		#Converts a python list to a PHP array

		$inputClean = str_replace(' u', '', $input);
		$inputClean = str_replace(', ', ',', $inputClean);
		$inputClean = str_replace('[u', '', $inputClean);
		$inputClean = str_replace('[', '', $inputClean);
		$inputClean = str_replace(']', '', $inputClean);
		$inputArray = preg_split('\',\'', $inputClean);
		return $inputArray;
	}
}
?>