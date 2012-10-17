<?php
class view{
	#Ignore
	public static function dummyClass($data){
		foreach ($data as $article) {
			echo $article->Name;
		}
	}
}
?>