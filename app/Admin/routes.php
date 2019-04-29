<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    
    //$router->resource('tables/medicines', eeProductController::class);
    $router->resource('tables/products', ProductController::class);
    $router->resource('tables/orders', OrderController::class);
    $router->resource('tables/employees', EmployeeController::class);
    $router->resource('tables/sales', SaleController::class);
    $router->resource('tables/positions', PositionController::class);
    $router->resource('tables/sale_details', SaleDetailController::class);
    $router->get('tables/sales/create', 'SaleController@createOrder');

    $router->post('computeOrder', 'SaleController@computeOrder');
    $router->post('storeOrder', 'SaleController@storeOrder');

    $router->resource('tables/abc', UsersController::class);


});
