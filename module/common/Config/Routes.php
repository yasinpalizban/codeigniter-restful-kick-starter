<?php

/*
 * Myth:Auth routes file.
 */

$routes->group('api', ['namespace' => 'Modules\Common\Controllers'], function ($routes) {
//$route->add('foo', 'Home::index', ['filter' => ['filterAlias', \App\Filters\SomeFilter::class]);

    $routes->resource('profile',['filter' => 'authJwt']);
    $routes->resource('chatContact',['filter' => 'authJwt']);
    $routes->resource('chatRoom',['filter' => 'authJwt']);
    $routes->resource('chatRoomMedia',['filter' => 'authJwt']);
    $routes->resource('chatPrivate',['filter' =>  'authJwt']);
    $routes->resource('chatPrivateMedia',['filter' => 'authJwt']);


    $routes->resource('user',['filter' => 'authJwt']);

    $routes->resource('setting',['filter' => 'authJwt']);
    $routes->resource('visitor',['filter' => 'authJwt']);
    $routes->resource('contact',['filter' => 'authJwt']);
    $routes->resource('contactMedia',['filter' => 'authJwt']);
    $routes->resource('newsCategory',['filter' => 'authJwt']);
    $routes->resource('newsSubCategory',['filter' => 'authJwt']);
    $routes->resource('newsPost',['filter' => 'authJwt']);
    $routes->resource('newsComment',['filter' => 'authJwt']);
    $routes->resource('newsMedia',['filter' => 'authJwt']);
    $routes->resource('viewOption',['filter' => 'authJwt']);
    $routes->resource('viewMedia',['filter' => 'authJwt']);
    $routes->resource('advertisement',['filter' => 'authJwt']);
    $routes->resource('advertisementMedia',['filter' => 'authJwt']);
    $routes->resource('requestCategory',['filter' => 'authJwt']);
    $routes->resource('requestPost',['filter' => 'authJwt']);
    $routes->resource('requestReply',['filter' => 'authJwt']);


//    $routes->get('x/new', 'X::new');
//    $routes->post('x/create', 'X::create');
//    $routes->post('x', 'X::create');   // alias
//    $routes->get('x', 'X::index');
//    $routes->get('x/show/(:segment)', 'X::show/$1');
//    $routes->get('x/(:segment)', 'X::show/$1');  // alias
//    $routes->get('x/edit/(:segment)', 'X::edit/$1');
//    $routes->post('x/update/(:segment)', 'X::update/$1');
//    $routes->get('x/remove/(:segment)', 'X::remove/$1');
//    $routes->post('x/delete/(:segment)', 'X::update/$1');
//
//    $routes->get('x/(:segment)/y/new', 'Y::new/$1');
//    $routes->post('x/(:segment)/y', 'Y::create/$1');
//    $routes->get('x/(:segment)/y', 'Y::index/$1');
//    $routes->get('x/(:segment)/y/(:segment)', 'Y::show/$1/$1');
//    $routes->get('x/(:segment)/y/(:segment)/edit', 'Y::edit/$1/$1');
//    $routes->put('x/(:segment)/y/(:segment)', 'Y::update/$1/$1');
//    $routes->patch('x/(:segment)/y/(:segment)', 'Y::update/$1/$1');
//    $routes->delete('x/(:segment)/y/(:segment)', 'Y::delete/$1/$1');

});


