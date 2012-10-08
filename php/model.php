<?php
class model{
	#Partially implemented
	public static function pythonStringToPHPArray($input){
		$inputClean = str_replace(' u', '', $input);
		$inputClean = str_replace('[u', '', $inputClean);
		$inputClean = str_replace(']', '', $inputClean);
		// $facebookDataClean = str_replace('[', '', $facebookDataClean);
		$inputArray = preg_split('\',\'', $inputClean);
		return $inputArray;
	}	
	public static function connectDatabase(){
		#Determines if online (dev) or online
		#Change username, database, etc. according to particular server
		$dir = getcwd();
		#Some local directory to diff btwn online and off
		$localDir="";
		if(strpos($dir,$localDir)){
			$database = "";
			$username = "root";
			$password = "";
			$host = "localhost";
		}else{
			$database = "";
			$username = "";
			$password = "";
			$host = "";
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
	public static function retrieveData($pdo,$table,$draft,$id_list){
		//Retrieves
		$variables = array(
			'idList' => $id_list,
			'draft' => $draft
			);

	    $stmt = $pdo->prepare("SELECT * FROM $table WHERE ID IN(:idList) AND Draft = :draft");
	    $stmt->execute($variables);
	    $data = array();
	    while($row = $stmt->fetch()) {
	        $data[]=(object) $row;
	    }
		return $data;
	}
	public static function setData($pdo,$table,$draft,$id_list){
		//Retrieves
		$variables = array(
			'idList' => $id_list,
			'draft' => $draft
			);
		// INSERT INTO $table (ID,Title,youtubeID,youtubeURL) VALUES (:id,:title,:youtubeID,:youtubeURL) ON DUPLICATE KEY UPDATE col1=0, col2=col2+1
	    $stmt = $pdo->prepare("SELECT * FROM $table WHERE ID IN(:idList) AND Draft = :draft");
	    $stmt->execute($variables);
	    $data = array();
	    while($row = $stmt->fetch()) {
	        $data[]=(object) $row;
	    }
		return $data;
	}

}
?>