<?php
include('php/model.php');
include('php/view.php');
include('php/controller.php');
include('php/youtube.php');
// exec("python facebook_parser.py",$output);
// var_dump($output);

#Reset maximum execution time (seconds). Avoid premature termination
set_time_limit(60*2);

#Retrieve data from facebook_parser.py, no filtering at the moment
$youtubeURL = $_POST['youtubeURL'];
$youtubeID = $_POST['youtubeID'];
$youtubeTitles = $_POST['youtubeTitles'];

#Convert python strings into PHP arrays
$youtubeURLArray = model::pythonStringToPHPArray($youtubeURL);
$youtubeIDArray = model::pythonStringToPHPArray($youtubeID);
$youtubeTitlesArray = model::pythonStringToPHPArray($youtubeTitles);

#Remove duplicates from the arrays
$youtubeURLArray = array_unique($youtubeURLArray);
$youtubeIDArray = array_unique($youtubeIDArray);
$youtubeTitlesArray = array_unique($youtubeTitlesArray);

#Print Arrays
print_r($youtubeURLArray);
print_r($youtubeIDArray);
print_r($youtubeTitlesArray);

#Setup youtube API connection
$yt = youtube::setYoutubeConnection();

#Name of playlist to modify
$playlistNameTitle = 'Add title';
$playlistNameDescription = 'Add description';

#Create new playlist, does nothing if playlist already exists
youtube::createNewYoutubePlaylist($yt,$playlistNameTitle,$playlistNameDescription);
$playlistToModify = youtube::getPlaylistObj($yt,$playlistNameTitle);

#Get List of video IDs
$playlistVideoIDs = youtube::getVideosInPlaylist($yt,$playlistToModify);
print_r($playlistVideoIDs);

#Check if playlist if full
// if (count($playlistVideoIDs)>=200) {
	#rerun with new playlist name
// 	youtube::controller($playlistNameTitle,$playlistNameDescription);
// }

#Cycle through each ID and add to playlist
foreach ($youtubeIDArray as $ID) {
	#Remove quotations from string to make proper URI
	$ID = str_replace('\'','',$ID);
	echo $ID.'
	';

	#Skip video if in array
	if(in_array($ID, $playlistVideoIDs)){
		echo 'Skipped: '.$ID.'<br>
		';
		continue;
	}

	#Add each video to the specified playlist
	youtube::addVideoToPlaylist($yt,$playlistToModify,$ID);
}
?>