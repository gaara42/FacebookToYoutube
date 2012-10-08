<?php
#Biafra Ahanonu
#07.10.2012
class youtube{
	#Set of functions to connect, read and modify youtube playlists
	#Code borrowed from https://developers.google.com/youtube/2.0/developers_guide_php and modified into functions
	public static function setYoutubeConnection(){
		// include_path('/Zend/library');
		// include_path('/Zend');
		$previousPaths = ini_get('include_path');
		#Zend Location
		$ZendLocation = ';YOUR_DIR\Zend\library';
		#Update the include path
		ini_set('include_path', $previousPaths.$ZendLocation);
		
		#The Zend dir must be in your include_path
		require_once 'Zend/Loader.php'; 

		#Load youtube module
		Zend_Loader::loadClass('Zend_Gdata_YouTube');

		#Load authentication modules, I used ClientLogin
		// Zend_Loader::loadClass('Zend_Gdata_AuthSub');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin'); 

		$authenticationURL= 'https://www.google.com/accounts/ClientLogin';
		$httpClient = 
		  Zend_Gdata_ClientLogin::getHttpClient(
		              $username = 'username@gmail.com',
		              $password = 'yourPassword',
		              $service = 'youtube',
		              $client = null,
		              #A short string identifying your application
		              $source = 'yourClient', 
		              $loginToken = null,
		              $loginCaptcha = null,
		              $authenticationURL);

		#Get a developer key and add string here
		$developerKey = 'yourDevKey';
		$applicationId = 'AppName';
		$clientId = 'ClientName';

		#Setup new youtube object
		$yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);

		#Assuming that $yt is a valid Zend_Gdata_YouTube object
		$yt->setMajorProtocolVersion(2);

		return $yt;
	}

	public static function createNewYoutubePlaylist($yt,$playlistName,$playlistDescription){
		#Creates a new playlist as specified

		#Obtain list of playlists from user feed
		$playlistListFeed = $yt->getPlaylistListFeed("default");

		#Search for playlist with same name, if exist, exit function (avoid duplicates)
		foreach ($playlistListFeed as $playlistListEntry) {
			if ($playlistListEntry->title->text==$playlistName) {
				echo 'Already playlist named: '.$playlistName;
				return;
			}
		}

		#Creates a new playlist
		$newPlaylist = $yt->newPlaylistListEntry();
		$newPlaylist->summary = $yt->newDescription()->setText($playlistDescription);
		$newPlaylist->title = $yt->newTitle()->setText($playlistName);

		#post the new playlist
		$postLocation = 'http://gdata.youtube.com/feeds/api/users/default/playlists';
		try{
		  $yt->insertEntry($newPlaylist, $postLocation);
		}catch(Zend_Gdata_App_Exception $e){
		  echo $e->getMessage();
		}

		return $playlistName;
	}

	public static function getPlaylistObj($yt,$playlistName){
		#Return a specific playlist in a users feed
		#NEED: try, catch block if playlist name isn't found

		#Get list of feeds
		$playlistListFeed = $yt->getPlaylistListFeed("default");

		#Cycle through each feed
		foreach ($playlistListFeed as $playlistListEntry) {
			// echo 'Title: ' . $playlistListEntry->title->text . "\n";
			#If feed is one specified, mark as returnin
			if ($playlistListEntry->title->text==$playlistName) {
				$playlistToAddTo = $playlistListEntry;
			}
		}
		return $playlistToAddTo;
	}
	
	public static function addVideoToPlaylist($yt,$playlistToAddTo,$videoID){
		#Add video to playlist

		#Get URL of video playlist
		$postUrl = $playlistToAddTo->getPlaylistVideoFeedUrl();

		#Video entry to be added
		$videoEntryToAdd = $yt->getVideoEntry($videoID);

		#create a new Zend_Gdata_PlaylistListEntry, passing in the underling DOMElement of the VideoEntry
		$newPlaylistListEntry = $yt->newPlaylistListEntry($videoEntryToAdd->getDOM());

		#post to playlist
		try{
		  $yt->insertEntry($newPlaylistListEntry, $postUrl);
		}catch(Zend_App_Exception $e){
		  echo $e->getMessage();
		}
	}

	public static function getVideosInPlaylist($yt,$playlistToAddTo){
		#Returns an array with playlist video IDs
		#NEED What if playlist is empty or doesn't exist? try/catch

		#Obtain list of video objs in playlist
		$playlistVideoFeed = $yt->getPlaylistVideoFeed($playlistToAddTo->getPlaylistVideoFeedUrl());

		#Array to add video IDs to
		$playlistVideoIDs = [];

		#Cycle through each video and add its ID to the array
		foreach ($playlistVideoFeed as $playlistVideoEntry) {
			$playlistVideoIDs[] = $playlistVideoEntry->getVideoId();
			// echo $playlistVideoEntry->getVideoId();
		}
		
		return $playlistVideoIDs;
	}
}
?>