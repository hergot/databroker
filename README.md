databroker
==========

#Installation

## Clone repository

`$ git clone git://github.com/hergot/databroker.git && cd databroker`

## Get composer

`$ curl -s https://getcomposer.org/installer | php`

## Run composer

`$ ./composer.phat install --dev`

## Create symbolic link for phpunit

`$ ln -nfs ./vendor/phpunit/phpunit/composer/bin/phpunit ./phpunit`

#Run unit tests

`./phpunit -c .`

##Run code coverage with unit tests

`./phpunit --coverage-html <folder path e.g. /tmp/coverage> -c .`


