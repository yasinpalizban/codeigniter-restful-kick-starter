<?php

/*
 * Myth:Auth routes file.
 */

$routes->group('api', ['namespace' => 'Modules\Payment\Controllers'], function ($routes) {


    $routes->resource('zarinPal',['filter' => 'authJwt']);
    $routes->resource('payPal',['filter' => 'authJwt']);


});


