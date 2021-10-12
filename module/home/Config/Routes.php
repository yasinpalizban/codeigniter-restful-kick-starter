<?php

/*
 * Myth:Auth routes file.
 */

$routes->group('api', ['namespace' => 'Modules\Home\Controllers'], function ($routes) {


    $routes->resource('msc');
    $routes->resource('home');
    $routes->resource('test');


});


