#!/bin/bash

php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"
php artisan admin:install