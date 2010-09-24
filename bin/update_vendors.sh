#!/bin/sh

DIR=`php -r "echo realpath(dirname(\\$_SERVER['argv'][0]));"`
VENDOR=$DIR/src/vendor

# Symfony
cd $VENDOR/symfony && git pull

# Doctrine ORM
cd $VENDOR/doctrine
git checkout master
git pull
git checkout -b v2.0.0-BETA4 2.0.0-BETA4
git checkout v2.0.0-BETA4

# Doctrine DBAL
cd $VENDOR/doctrine-dbal
git checkout master
git pull
git checkout -b v2.0.0-BETA4 2.0.0-BETA4
git checkout v2.0.0-BETA4

# Doctrine common
cd $VENDOR/doctrine-common
git checkout master
git pull
git checkout -b v2.0.0-RC1 2.0.0-RC1
git checkout v2.0.0-RC1

# Doctrine migrations
cd $VENDOR/doctrine-migrations && git pull

# Doctrine MongoDB
cd $VENDOR/doctrine-mongodb
git checkout master
git pull
git checkout -b v1.0.0BETA1 1.0.0BETA1
git checkout v1.0.0BETA1

# Twig
cd $VENDOR/twig && git pull

# Zend Framework
cd $VENDOR/zend && git pull
