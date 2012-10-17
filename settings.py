#Python settings for FacebookToYoutube
#2012.10.16

#Login page, use mobile Facebook, has simpler HTML source
LOGIN_URL = 'http://m.facebook.com/login/'

#Page to be crawled
CRAWL_URL = 'https://m.facebook.com/someURL'

#Login information should be encrypted, plaintext here
#Login information for facebook
USERNAME = ''
#Facebook password
PASSWORD = ''

#Debug, 0 = off, 1 = on
DEBUG_OUTPUT = 1

#Page you will be crawling, this can be altered by a function calling facebook_parser.py
POST_URL = 'http://localhost/index.php'

#Output raw data
DATA_FILE = ''
#List of links
HTML_FILE = ''