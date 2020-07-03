# FacebookToYoutube

## Overview
* This app downloads pages that need an authenticated Facebook login, finds links to youtube videos and adds those videos to a chosen user's playlist.
* Full description and installation options coming in next couple days.

### Features

* Grab Facebook pages
* Automatically add videos to youtube

## What's New
* Added FacebookToYoutube to GitHub (2012.10.07)
* Automatically create new playlist when current one is full (2012.10.16)

### In Development
* Store youtube IDs on a MySQL database
* Webpage front-end, display links downloaded and in database.
* OOP implementation of Facebook downloader, to generalize
* OOP implementation of PHP code

## Installation

### Dependencies
* Python: mechanize and HTMLParser needed
* PHP: Zend Gdata needed
* Uncomment extension=php_openssl.dll in your php.ini file to remove the following error: 'Unable to find the socket transport "ssl" – did you forget to enable it when you configured PHP?''

### Use
* facebook_parser.py is the main file, it authenticates a Facebook session, downloads the webpage and parses it for Youtube videos.
* /php/youtube.php contains a set of functions to access the Youtube API.
* /php/model.php contains python list to PHP array parser.
* /php/controlelr.php and /php/view.php do nothing at the moment.

### User Specific Data
* There are several areas in the code, to be marked EDIT_HERE, where information specific to your accounts should be entered.

## License
Copyright (C) 2012 Biafra Ahanonu

"None are so hopelessly enslaved as those who falsely believe they are free."
                                              -Johann Wolfgang von Goethe

This license apply to all the files inside this program unless noted different for some files or portions of code inside these files.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation. http://www.gnu.org/licenses/gpl.html

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/gpl.html
