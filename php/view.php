<?php
class view{
	#Ignore
	public static function displayUpdates($data){
		foreach ($data as $article) {
			echo $article->Name;
		}
	}
}
?>