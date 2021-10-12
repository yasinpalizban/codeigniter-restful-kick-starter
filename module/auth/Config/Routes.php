<?php

/*
 * Core Auth  routes file.
 */

$routes->group('api', ['namespace' => 'Modules\Auth\Controllers'], function ($routes) {

    $routes->resource('group', ['filter' => 'authJwt']);
    $routes->resource('permission', ['filter' => 'authJwt']);
    $routes->resource('groupPermission', ['filter' => 'authJwt']);
    $routes->resource('userPermission', ['filter' => 'authJwt']);

    $routes->group('auth', function ($routes) {

        $routes->post('signin-jwt', 'Auth::signInJwt', ['filter' => 'isSignIn']);
        $routes->post('signin', 'Auth::signIn', ['filter' => 'isSignIn']);
        $routes->get('signout', 'Auth::signOut', ['filter' => 'authJwt']);


        $routes->post('signup', 'Auth::signUp', ['filter' => 'isSignIn']);
        $routes->post('forgot', 'Auth::forgot', ['filter' => 'isSignIn']);

        $routes->post('reset-password-email', 'Auth::resetPasswordViaEmail', ['filter' => 'isSignIn']);
        $routes->post('reset-password-sms', 'Auth::resetPasswordViaSms', ['filter' => 'isSignIn']);

        $routes->post('activate-account-email', 'Auth::activateAccountViaEmail', ['filter' => 'isSignIn']);
        $routes->post('send-activate-email', 'Auth::sendActivateCodeViaEmail', ['filter' => 'isSignIn']);

        $routes->post('activate-account-sms', 'Auth::activateAccountViaSms', ['filter' => 'isSignIn']);
        $routes->post('send-activate-sms', 'Auth::sendActivateCodeViaSms', ['filter' => 'isSignIn']);

    });

});


