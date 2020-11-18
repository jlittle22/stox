# Comp 20 Final Project

## Requirements

* Must use HTML/CSS/Javascript and/or jQuery
* Must use a database
* Must use a server side program - you my use PHP or  node.js / Heroku to accomplish this
* Must use an external API


## Set up your local server

1. Validate that php is installed on your box:
    
       php -v

   If it's not, go get it.

2. Set up a local test server in the directory of file you'd like to run:

       php -S localhost:8000

3. Connect to server by typing the following in your browser's URL bar:

       localhost:8000/{target_file}
   
   It's important to note that the local server **adopts the directory
   structure around it**. So as long as your target file in the top level
   of the server directory, any file paths that were hardcoded into the
   file should be all set.


## Re: Databases

We won't be able to access server side databases from our code, so
it's best to exclude those specific lines of code and leave a TODO
comment indicating what needs to be done in that line. Build
everything else! Add placeholders as necessary. Just make it easy
to plug in the right DB functions and get it working. 

## Notes 
* Social media cross platforming (Spotify x Apple Music, Facebook x Snapchat,
  etc)
* Stock API to practice investing as a web app

## Web Radio Player w/ Video

### Spotify API: 
* Get user data like most played

### YouTube API (Embedded YouTube Player):
* Play videos of those Songs

## Practice Stocks

### Stock API (tbd):
* Simulate buying and selling stocks at certain times 
* Display charts of various stocks

### DB:
* Collect user's networth and investments 
* Manage user login and registration

## Room Reservation / Covid Test Scheduling 
* Collect availabilities and schedule tests/reservations in least populated
  time slots
