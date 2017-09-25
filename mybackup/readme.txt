phpMyBackup v.0.4 Beta - Documentation
Homepage: http://www.nm-service.de/phpmybackup
Copyright (c) 2000-2001 by Holger Mauermann, mauermann@nm-service.de

phpMyBackup is distributed in the hope that it will be useful for you, but
WITHOUT ANY WARRANTY. This programm may be used freely as long as all credit
and copyright information are left intact.

IMPORTANT
---------
PHPMYBACKUP IS STILL A BETA VERSION. I CAN'T TRY PHPMYBACKUP WITH ALL POSSIBLE
MYSQL-COLUMNTYPES, -KEYS ETC. SO USE IT AT YOUR ON RISK! I AM NOT RESPONSIBLE
FOR ANY LOSS OF DATA!

Description
-----------
phpMyBackup creates backups from a mySQL-Database at given time intervals and
stores them in a gz-compressed file on your webserver. An interface to restore
the database is included.

Requirements
------------
- PHP3/4 enabled Webserver
- MySQL database
- optional: PHP with zlib-support for compression

Installation
------------
Save the files "backup.php" and "restore.php" anywhere on your webserver, for
example create a directory "phpmybackup" and put the files in it. You should
protect this directory with a .htaccess file! Change the variables at the top
of "backup.php" to your needs.
To execute "backup.php", you can add the lines
   <?
   include "phpmybackup/backup.php";
   ?>
at the bottom of a frequently accessed file, for example your index page.

To restore the database, go to "phpmybackup/restore.php" and choose the file
you want to restore.

History
-------

v. 0.4 Beta:
- Bugfix: "NULL" - columns were stored/restored as "NOT NULL" - columns
- You can now easily download the backups to your local machine

v. 0.3 Beta:
- first official release
