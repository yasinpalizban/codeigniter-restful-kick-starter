<?php

/*
 * Myth:Auth routes file.
 */

$routes->group('api', ['namespace' => 'Modules\App\Controllers'], function ($routes) {

    $routes->resource('overView',['filter' => 'authJwt']);
    $routes->resource('graph',['filter' => 'authJwt']);

});


