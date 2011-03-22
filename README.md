#Single Sign-on Implementation

##Description

A basic implementation of a modern sign-in mechanism, supports logging in with:

* Google Account via OpenID
* Yahoo! Account via OpenID
* Facebook via OAuth
* Twitter via OAuth
* Internal, old-school login/password combination

For more details please visit http://doppnet.com

##Requiremnets

* PHP >= 5.2
* MySQL Database with InnoDB engine

#Installation
* Place code in path served by your webserver
* Enter valid configuration for the database access and the OAuth integration. Config files are located in the *application/config* directory.
* Execute the following SQL to create required tables:

    CREATE TABLE `users` (
      `id` int(10) unsigned NOT NULL auto_increment,
      `name` varchar(200) default NULL,
      `email` varchar(200) default NULL,
      PRIMARY KEY  (`id`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8


    CREATE TABLE `associations` (
      `id` int(10) unsigned NOT NULL auto_increment,
      `user_id` int(10) unsigned NOT NULL,
      `openid` varchar(255) default NULL,
      `facebook` varchar(255) default NULL,
      `twitter` varchar(255) default NULL,
      `email` varchar(255) default NULL,
      `password` varchar(32) default NULL,
      PRIMARY KEY  (`id`),
      UNIQUE KEY `openidassociations-unique-openid` USING BTREE (`openid`),
      KEY `openidassociations-users-userid` USING BTREE (`user_id`),
      CONSTRAINT `openidassociations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8

##License
This code is released under MIT License.

However the following items included in this repository are licensed differently:

* File `/resources/images/background.jpg` is a photography copyrighted by Sebastian Bober http://sebastianbober.com. See http://www.flickr.com/photos/sebastianbober/5261257281/ for license details
* KOHANA framework released under BSD License
* jQuery released under MIT/GPL2 licenses
