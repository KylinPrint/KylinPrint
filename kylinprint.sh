#!/bin/bash

cd `dirname $0`

#TODO  运行环境检测
if [ -f ".env" ];then
    echo ".env found"
else
    echo ".env not found, now creating..."
    cp .env.example .env
    echo "now edit .env and then run `basename $0` again"
    exit 0
fi

if [ `command -v composer > /dev/null 2>&1;echo $?` -eq 1 ];then
    echo "composer not found, exiting..."
    exit 1
else
    echo "composer found"
fi
composer update

if [ `command -v php > /dev/null 2>&1;echo $?` -eq 1 ];then
    echo "php not found, exiting..."
    exit 1
else
    echo "php found"
fi

php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
php artisan migrate
php artisan key:generate

php artisan admin:install
php artisan admin:generate-menu
