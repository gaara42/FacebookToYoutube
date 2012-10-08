<?php
class controller{
	#Ignore
	public static function displayArticles(){
		$pdo = model::connectDatabase();
		$data = model::retrieveData($pdo,'','','');
		view::displayUpdates($data);
	}
}
?>