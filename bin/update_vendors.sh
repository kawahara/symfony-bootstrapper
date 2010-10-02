#!/bin/sh

DIR=`php -r "echo realpath(dirname(\\$_SERVER['argv'][0]));"`
VENDOR=$DIR/src/vendor

# Symfony
cd $VENDOR/symfony && git pull

# Doctrine ORM
cd $VENDOR/doctrine
git checkout master
git pull

# Doctrine DBAL
cd $VENDOR/doctrine-dbal
git checkout master
git pull

# Doctrine common
cd $VENDOR/doctrine-common
git checkout master
git pull

# Doctrine migrations
cd $VENDOR/doctrine-migrations && git pull

# Doctrine MongoDB
cd $VENDOR/doctrine-mongodb
git checkout master
git pull

# Twig
cd $VENDOR/twig && git pull

# Zend Framework
cd $VENDOR/zend && git pull
