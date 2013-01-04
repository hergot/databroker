databroker
==========

##Purpose

Databroker helps application to be more elastic e.g. you can easily turn on/off caching for various data fetch (database queries, rest and soap calls, file fetch, ...).
It implements plugin architecture so it is easy to extend databroker with additional functionality (cache, monitor, ...).

##Installation

### Clone repository

`$ git clone git://github.com/hergot/databroker.git && cd databroker`

### Get composer

`$ curl -s https://getcomposer.org/installer | php`

### Run composer

`$ ./composer.phar install --dev`

### Create symbolic link for phpunit

`$ ln -nfs ./vendor/phpunit/phpunit/composer/bin/phpunit ./phpunit`

##Run unit tests

[![Build Status](https://secure.travis-ci.org/hergot/databroker.png)](http://travis-ci.org/hergot/databroker)

`$ ./phpunit -c .`

###Run code coverage with unit tests

`$ ./phpunit --coverage-html <folder path e.g. /tmp/coverage> -c .`

###Notes

####APC

You have to enable apc for cli - apc.enable_cli=1 in php.ini file or run phpunit with --exclude-group apc

`$ ./phpunit --exclude-group apc -c .`


