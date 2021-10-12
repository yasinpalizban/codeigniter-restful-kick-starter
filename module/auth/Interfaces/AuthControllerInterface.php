<?php

namespace Modules\Auth\Interfaces;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * Expected behavior of a Security.
 */
interface AuthControllerInterface
{

    public function signInJwt(): ResponseInterface;

    public function signIn(): ResponseInterface;

    public function signOut(): ResponseInterface;


    public function signUp(): ResponseInterface;

    public function forgot(): ResponseInterface;

    public function resetPasswordViaSms(): ResponseInterface;

    public function resetPasswordViaEmail(): ResponseInterface;

    public function activateAccountViaEmail(): ResponseInterface;

    public function sendActivateCodeViaEmail(): ResponseInterface;

    public function activateAccountViaSms(): ResponseInterface;

    public function sendActivateCodeViaSms(): ResponseInterface;
}

