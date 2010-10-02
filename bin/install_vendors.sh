#!/bin/sh

# initialization
DIR=`php -r "echo realpath(dirname(\\$_SERVER['argv'][0]));"`
VENDOR=$DIR/src/vendor

if [ -d $VENDOR ]; then
  rm -rf $VENDOR/*
else
  mkdir $VENDOR
fi

cd $VENDOR

# Symfony
git clone git://github.com/symfony/symfony.git symfony

# Doctrine ORM
git clone git://github.com/doctrine/doctrine2.git doctrine
cd doctrine
cd $VENDOR

# Doctrine DBAL
git clone git://github.com/doctrine/dbal.git doctrine-dbal
cd doctrine-dbal
cd $VENDOR

# Doctrine Common
git clone git://github.com/doctrine/common.git doctrine-common
cd doctrine-common
cd $VENDOR

# Doctrine migrations
git clone git://github.com/doctrine/migrations.git doctrine-migrations

# Doctrine MongoDB
git clone git://github.com/doctrine/mongodb-odm.git doctrine-mongodb
cd doctrine-mongodb
cd $VENDOR

# Twig
git clone git://github.com/fabpot/Twig.git twig

# Zend Framework
git clone git://github.com/zendframework/zf2.git zend
