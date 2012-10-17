<?php
#Settings for FacebookToYoutube
#2012.10.16

#Time (seconds) for script to stop executing
define("SCRIPT_TIMEOUT", 60*2)

#String in filesystem path to differentiate local from online
define("LOCAL_ID", '');

#Local MySQL settings
define("MYSQL_DATABASE_LOCAL",'');
define("MYSQL_USERNAME_LOCAL",'');
define("MYSQL_PASSWORD_LOCAL",'');
define("MYSQL_HOST_LOCAL",'');

#Online MySQL settings
define("MYSQL_DATABASE_ONLINE",'');
define("MYSQL_USERNAME_ONLINE",'');
define("MYSQL_PASSWORD_ONLINE",'');
define("MYSQL_HOST_ONLINE",'');

#Name of the playlist to be modified
define("YOUR_PLAYLIST", '');
#Description of playlist
define("YOUR_DESCRIPTION", '');

#Absolute or relative location of Zend folder
define("ZEND_LOCATION", ';C:\some\path\to\Zend\library');
#Username

define("YOUTUBE_USERNAME", '');
#Password, you can use another function to grab this if you want higher security
define("YOUTUBE_PASSWORD", '');

#Name of your application, same as dev key app name
define("APP_SOURCE", '');
#Dev key obtained from Google
define("DEV_KEY", '');
#Name of your application
define("APP_ID", '');
#Name of 'client', just make same as APP_ID
define("CLIENT_ID", '');
?>
