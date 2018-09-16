# YoutubeAPI
App that allows you to search for youtube channels, get their playlists and view all the videos in the selected playlist.

## Step 1: Turn on the YouTube Data API

* Visit [Google API page](https://console.developers.google.com/apis/library/youtube.googleapis.com?filter=category:youtube&id=125bab65-cfb6-4f25-9826-4dcc309bc508&project=smart-period-216311&folder&organizationId) and enable Youtube API
* Find “Project” tab in the YouTube API console. Click it and choose “Create project” button from the drop-down menu
* The new project window will appear in popup. Name your project and click “Create” button to proceed further
* In order to make your project active, click on “YouTube Data API” web page. You will be redirected to the Dashboard section
* Click “Enable” button
* Go to “Credentials”
* Select “Create credentials” option and click “OAuth Client ID” in the drop-down list
* Set "Authorised redirect URIs" to Root URL/oauth2callback -- e.g. http://localhost/YoutubeAPI/oauth2callback
* Save credentials and download the client_secret json file
* Place the json file in the root directory

## Step 2: Edit the config.php file

* Go to config/Config.php
* Change APPROOT constant to your root URL --- the default APPROOT is http://localhost/YoutubeAPI
* Change the CHANNEL constant to the default channel you want to load when the page loads --- the default channel is Google Developers

To search and view channels you have to login first using your Google account.
