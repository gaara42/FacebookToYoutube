<?php
class youtube{
	#Set of functions to connect, read and modify youtube playlists
	#Code borrowed from https://developers.google.com/youtube/2.0/developers_guide_php and modified into functions
	public static function setYoutubeConnection(){
		// include_path('/Zend/library');
		// include_path('/Zend');

		#Obtain include paths, to modify
		$previousPaths = ini_get('include_path');
		#Zend Location
		$ZendLocation = ZEND_LOCATION;
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
		              $username = YOUTUBE_USERNAME,
		              $password = YOUTUBE_PASSWORD,
		              $service = 'youtube',
		              $client = null,
		              #A short string identifying your application
		              $source = APP_SOURCE, 
		              $loginToken = null,
		              $loginCaptcha = null,
		              $authenticationURL);

		#Get a developer key and add string here
		$developerKey = DEV_KEY;
		$applicationId = APP_ID;
		$clientId = CLIENT_ID;

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
				echo 'Already playlist named: '.$playlistName.'
				';
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

		#Array to add video IDs to
		$playlistVideoIDs = [];

		#Just get start-index so we can capture all videos
		$scrawlFeed = array(1=>49,50=>99,100=>149,150=>199);

		#Base URL that we will modify and pass to our youtube obj
		$playlistURL = $playlistToAddTo->getPlaylistVideoFeedUrl();

		#API only allows 50 result to be passed
		#https://developers.google.com/youtube/2.0/reference#start-indexsp
		$maxResult = 50;

		foreach ($scrawlFeed as $startIndex => $value) {
			echo 'Indexing...'.$startIndex.' | '.($startIndex+$maxResult).'---';

			#Modify loop playlist URL
			$loopPlaylistURL = $playlistURL."?start-index=$startIndex&max-results=$maxResult";

			#Obtain list of video objs in playlist
			$playlistVideoFeed = $yt->getPlaylistVideoFeed($loopPlaylistURL);

			#Cycle through each video and add its ID to the array
			foreach ($playlistVideoFeed as $playlistVideoEntry) {
				$playlistVideoIDs[] = $playlistVideoEntry->getVideoId();
				// echo $playlistVideoEntry->getVideoId();
			}
		}
		
		return $playlistVideoIDs;
	}
}
?>