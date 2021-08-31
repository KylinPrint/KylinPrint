<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('printers', PrinterController::class);
    $router->resource('brands', BrandController::class);
    $router->resource('manufactors', ManufactorController::class);
    $router->resource('solutions', SolutionController::class);
    $router->resource('files', FileController::class);
    $router->resource('tags', TagController::class);
    $router->resource('tag_binds', TagBindController::class);
    $router->resource('project_tags', ProjectTagController::class);
});
