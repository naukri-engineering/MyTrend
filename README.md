MyTrend
=======
An ingenious tool to monitor data growth trend and server space utilization simple enough to be customized easily.

* Monitor Everything - Everything inside your MySQL can be monitored (Database Size, Connections, Slow Queries etc)
* Analyze MySQL data collected over a period of time
* Capacity Planning - Plan the growth of your business and be able to predict the future and apply the financial resources more accurately.
* Cool Interface; All major browsers supported.
* Fully customizable using web-based customization interface
* True Open Source - No Enterprise version, the best of MyTrend is offered for free.


Installation
------------
1. Download and extract MyTrend.

   To download and extract the files, on a typical Unix/Linux command line, use
   the following commands (assuming you want version x.y of MyTrend in .tar.gz
   format):

     tar -zxvf MyTrend-x.y.tar.gz
     
     unzip MyTrend-x.y.zip

   This will create a new directory MyTrend-x.y/ containing all MyTrend files and
   directories. Then, to move the contents of that directory into a directory
   within your web server's document root or your public HTML directory,
   continue with this command:

     mv MyTrend-x.y/* MyTrend-x.y/.htaccess /path/to/your/installation

2. Create the MyTrend database.

   Because MyTrend stores all site information in a database, you must create
   this database in order to install MyTrend, and grant MyTrend certain database
   privileges (such as the ability to create tables). For details, consult
   INSTALL.mysql.txt. 

   Take note of the username, password, database name, and hostname as you
   create the database. You will enter this information during the install.

3. Run the install script.

   To run the install script, point your browser to the base URL of your
   website (e.g., http://localhost/MyTrend/).

   You will be guided through several screens to set up the database, add the
   site maintenance account and provide basic web site settings.

   During installation, several files and directories need to be created, which
   the install script will try to do automatically. However, on some hosting
   environments, manual steps are required, and the install script will tell
   you that it cannot proceed until you fix certain issues. This is normal and
   does not indicate a problem with your server.

   The most common steps you may need to perform are:

   a. Permissons.

      For example, on a Unix/Linux command line, you can grant everyone
      (including the web server) permission to write to the sites/default
      directory with this command:

      chmod -R 777 cache

      chmod -R 777 config	

      Be sure to set the permissions back on the "config" after the installation 
      is finished!

4. Set up independent "cron" maintenance jobs.

   MyTrend modules have tasks that must be run periodically

   As an example for how to set up this automated process, you can use the
   crontab utility on Unix/Linux systems. The following cron runs daily, 
   on the hour:

   1 0 * * * /usr/local/php/bin/php /path/to/your/installation/cronMyTrend.php D email@example.com



Contributors
------------
* Amardeep Vishwakarma


Contact Us
----------
engineering[at]naukri.com

