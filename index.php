<?php
include('php/settings.php');
include('php/model.php');
include('php/view.php');
include('php/controller.php');
include('php/youtube.php');

#Reset maximum execution time (seconds). Avoid premature termination
set_time_limit(SCRIPT_TIMEOUT);

#Retrieve data from facebook_parser.py, no data filtering at the moment
$youtubeURL = $_POST['youtubeURL'];
$youtubeID = $_POST['youtubeID'];
$youtubeTitles = $_POST['youtubeTitles'];

#Convert python strings into PHP arrays
$youtubeURLArray = model::pythonStringToPHPArray($youtubeURL);
$youtubeIDArray = model::pythonStringToPHPArray($youtubeID);
$youtubeTitlesArray = model::pythonStringToPHPArray($youtubeTitles);

#Remove duplicates from the arrays, re-index
$youtubeURLArray = array_values(array_unique($youtubeURLArray));
$youtubeIDArray = array_values(array_unique($youtubeIDArray));
$youtubeTitlesArray = array_values(array_unique($youtubeTitlesArray));

#Print Arrays
print_r($youtubeURLArray);
print_r($youtubeIDArray);
print_r($youtubeTitlesArray);

#Setup youtube API connection
$yt = youtube::setYoutubeConnection();

#Name of playlist to modify
$playlistNameTitle = YOUR_PLAYLIST;
$playlistNameDescription = YOUR_DESCRIPTION;

#Create new playlist, does nothing if playlist already exists
youtube::createNewYoutubePlaylist($yt,$playlistNameTitle,$playlistNameDescription);
$playlistToModify = youtube::getPlaylistObj($yt,$playlistNameTitle);

#Get List of video IDs
$playlistVideoIDs = youtube::getVideosInPlaylist($yt,$playlistToModify);
echo 'List contains: 
';
print_r($playlistVideoIDs);

#Check if playlist if full
echo 'Number of videos in playlist: '.count($playlistVideoIDs).' of 200 max.
';
if (count($playlistVideoIDs)>=200) {
	#Rerun with new playlist name
	youtube::controller($playlistNameTitle.' alt',$playlistNameDescription);
}

#To move through Title Array
$videoID = -1;
echo '
		';
#Cycle through each ID and add to playlist
foreach ($youtubeIDArray as $ID) {
	#Remove quotations from string to make proper URI
	$ID = str_replace('\'','',$ID);
	echo $ID.': ';
	$videoID++;
	#Skip video if in array
	if(in_array($ID, $playlistVideoIDs)){
		echo 'Skipped...'.$youtubeTitlesArray[$videoID].'
		';
		continue;
	}else{
		echo 'Adding...'.$youtubeTitlesArray[$videoID].'
		';
	}

	#Add each video to the specified playlist
	youtube::addVideoToPlaylist($yt,$playlistToModify,$ID);
}
// include('c3_data.html');
?>