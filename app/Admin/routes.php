<?php

use Illuminate\Routing\Router;

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
});
