#!/bin/bash

#TODO  运行环境检测
composer update

php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
php artisan migrate
php artisan key:generate

php artisan admin:install
php artisan admin:generate-menu
