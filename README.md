Set up your local server:

1. Validate that php is installed on your box:
    
       php -v

   If it's not, go get it.

2. Set up a local test server in the directory of file you'd like to run:

       php -S localhost:8000

3. Connect to server by typing the following in your browser's URL bar:

       localhost:8000/{target_file}
   
   It's important to note that the local server adopts the directory
   structure around it. So as long as your target file in the top level
   of the server directory, any file paths that were hardcoded into the
   file should be all set.


   Re: Databases -
   We won't be able to access server side databases from our code, so
   it's best to exclude those specific lines of code and leave a TODO
   comment indicating what needs to be done in that line. Build
   everything else! Add placeholders as necessary. Just make it easy
   to plug in the right DB functions and get it working. 
