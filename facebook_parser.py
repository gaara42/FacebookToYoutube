#!/Python27/env python
#Biafra Ahanonu
#2012.10.16
#Based on:
#http://ubuntuincident.wordpress.com/2011/11/08/download-login-protected-pages-with-python-using-mechanize-and-splinter-part-3/
#NEED: Wrap functions inside class to make proper object

#Import dependencies, must have mechanize installed
import mechanize,os,re
#ParseResponse used as a check for form data on a page
from mechanize import ParseResponse, urlopen, urljoin
#To help us crawl through downloaded page's content
from HTMLParser import HTMLParser
#Import User settings, setting variables are CAPITALIZED
from settings import *

def getURLFormInformation():
    #Small function to craw a web page and print all information about forms on the page, for debug purposes

    #Open URL
    response = urlopen(LOGIN_URL)

    #Obtain form information
    forms = ParseResponse(response, backwards_compat=False)
    form = forms[0]
    print form

    #Add values to form
    form['email']=USERNAME
    form['pass']=USERNAME
    print form['email'],form['pass']
    os.system('pause')

def getFacebookPage():
    #Opens authenticated connection to Facebook and downloads page

    #Setup mechanize browser
    browser = mechanize.Browser()
    browser.set_handle_robots(False)
    browser.addheaders = [('User-agent', 'Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.7 (KHTML, like Gecko) Chrome/7.0.517.41 Safari/534.7')]
    # cookies = mechanize.CookieJar()
    # browser.set_cookiejar()

    #Connect to login URL
    browser.open(LOGIN_URL)
    print 'Login page: connected!'

    #Select the form, nr=0 means to use the first form you find, else enter the name='form html id name'
    browser.select_form(nr=0)# name="login_form"

    #Fill the username and password in the form
    #Might have to check your particular webpage for actual name of input id
    browser['email'] = USERNAME# browser['username'] = USERNAME
    browser['pass'] = PASSWORD# browser['password'] = PASSWORD
       
    #Clicks the submit button for a particular form
    result = browser.submit()
    print 'Login page: accessing...'
    #print res.get_data()

    #Open URL to download
    result = browser.open(CRAWL_URL)
    print 'Downloaded source:',CRAWL_URL

    #Logout of our session to prevent problems...
    for link in browser.links(url_regex="/logout"):
        print 'Following:',link.url
        req = browser.click_link(url=link.url)
        req = browser.follow_link(url=link.url)
        if 'Facebook' in req.get_data():
            print 'Logged out!'
            sepline()

    #Return HTML source
    return result.get_data()

class FacebookHTMLParser(HTMLParser):
    #Subclass that overrides the HTMLParser handler methods
    #Crawls through the webpage finding tags, adds youtube videos to a list when found. 
    #Does not return anything, call variables from object, they are public

    #Setup variables as public
    #Youtube URL
    youtubeBaseVideo = []
    #Video ID
    youtubeVideo = []
    associateUrlTitleSwitch = 0
    TempUrlTitle = ''
    #List of actual title of videos
    UrlTitles = []

    def handle_starttag(self, tag, attrs):
        #Each time HTMLParser sees a start tag, this function is called

        for item in attrs:
            #Facebook uses the following class to ID attachment titles
            # if item[1]=='storyAttachmentTitle':
            #     #Switch to state where we will record the title when calling handle_data
            #     self.associateUrlTitleSwitch = 1
            #     print item[1]

            #Check and see if found Youtube link
            if '%2Fwatch%3Fv%3' in item[1]:
                #Regular expression for youtube URLs in raw html
                go = re.search('v%3D[0-9A-Za-z_-]*(&|%26)',item[1])
                youtubeURL = go.group()
                
                #Remove characters, get only base video ID
                youtubeURL = youtubeURL.replace('v%3D','')
                youtubeURL = youtubeURL.replace('&','')
                youtubeURL = youtubeURL.replace('%26','')
                if DEBUG_OUTPUT == 1:
                    print youtubeURL
                
                #Add video to list
                self.youtubeBaseVideo.append('http://www.youtube.com/watch?v='+youtubeURL)

                #Open a mechanize browser and get video title
                br = mechanize.Browser()
                #replace www with m for faster, but then need to parse out 'YouTue - '
                br.open('http://www.youtube.com/watch?v='+youtubeURL)
                youtubeTitle = br.title()
                # youtubeTitle.replace('YouTube ','')

                #Remove commas so the PHP regexp isn't messed up later
                youtubeTitle = youtubeTitle.replace(',','')
                if DEBUG_OUTPUT == 1:
                    print youtubeTitle

                #Add video ID to list
                # print self.youtubeBaseVideo
                self.youtubeVideo.append(youtubeURL)

                #Add video title to list
                self.UrlTitles.append(youtubeTitle)
                # self.TempUrlTitle = ''

    def handle_endtag(self, tag):
        pass
    def handle_data(self, data):
        pass
        #Ignore below
        #If just encountered the URL title, record it then switch out of state
        # print data
        # if self.associateUrlTitleSwitch == 1:
        #     self.TempUrlTitle = data
        #     self.associateUrlTitleSwitch = 0
        #     # print self.TempUrlTitle
        # if 'watch?' in data[0:len('watch?')]:
        #     pass
            # print 'http://www.youtube.com/'+data
            # http://www.youtube.com/watch?v=OBl4pp0Sfko
            # self.youtubeBaseVideo.append('http://www.youtube.com/'+data)
            # # print self.youtubeBaseVideo
            # self.youtubeVideo.append(data)
            # self.UrlTitles.append(self.TempUrlTitle)
            # self.TempUrlTitle = ''

def sendDataToURL(inputData):
    #Sends python data to PHP script
    #Thanks TheBestJohn for a good starting point.

    #Import urllib libraries
    import urllib2, urllib

    #List of POST variables to pass, structure: [(varname,value)]
    dataToSend=[('youtubeURL',inputData[0]),('youtubeTitles',inputData[1]),('youtubeID',inputData[2])]    

    #Convert data
    dataToSend=urllib.urlencode(dataToSend)

    #URL to send POST data to
    path=POST_URL

    #Send the data
    req=urllib2.Request(path, dataToSend)
    req.add_header("Content-type", "application/x-www-form-urlencoded")

    #Read the resulting page
    page=urllib2.urlopen(req).read()

    sepline()
    print 'Submitted data to',path
    print page

def sepline():
    #Prints a separating line, function only so easy style change
    print '_______'

def writeDataToFile(inputData):
    #Raw HTML written to file for later use or debug

    f = open(DATA_FILE,'w')
    f.write(inputData)
    # html2text.html2text(inputData)
    f.close()

def writeDataToHTML(URLs,Titles):
    #Creates an HTML file with links to videos
    
    return
    f = open(HTML_FILE,'w')
    TitleIterator = iter(Titles)
    for URL in URLs:
        Title = TitleIterator.next()
        try:
            f.write('<a href=\''+URL+'\'>'+Title+'</a><br>')
        except UnicodeDecodeError:
            raise
        else:
            f.write('<a href=\''+URL+'\'>Invalid</a><br>')
        finally:
            pass
    f.close()

def main():
    #Main function, get facebook data, parse and send to PHP

    #Get raw HTML from facebook page
    facebookData = getFacebookPage()

    #Setup HTML parser object
    parser = FacebookHTMLParser()

    #Import raw html file 
    # f = open('c3_data.txt','r')
    # facebookData = f.read()
    # print facebookData

    #Parse facebook data
    parser.feed(facebookData)

    #Print youtube URLs
    if DEBUG_OUTPUT == 1:
        sepline()
        print parser.youtubeBaseVideo
        print parser.youtubeVideo
        print parser.UrlTitles

    #Send data to php script for processing
    sendDataToURL([parser.youtubeBaseVideo,parser.UrlTitles,parser.youtubeVideo])

    #Write the data to a file
    writeDataToFile(facebookData)

    #Create webpage with list of links
    writeDataToHTML(parser.youtubeBaseVideo,parser.UrlTitles)

    sepline()
    print 'Done!'
    os.system('pause')

#We are in main script, start
if __name__ == "__main__":
    main()
