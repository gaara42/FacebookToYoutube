<?php
class controller{
	public static function dummyFunction(){
		$pdo = model::connectDatabase();
		$data = model::retrieveData($pdo,"article",0,42);
		view::displayUpdates($data);
	}
}
?>