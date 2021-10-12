<?php


namespace Modules\Auth\Interfaces;

use Modules\Auth\Entities\AuthEntity;

/**
 * Expected behavior of a Security.
 */
interface AuthServiceInterface
{

    public function signInJwt(AuthEntity $entity): array;

    public function signIn(AuthEntity $entity): array;

    public function signOutJwt(object $userData):void;

    public function signUp(AuthEntity $entity):void;

    public function forgot(AuthEntity $entity):void;

    public function resetPasswordViaSms(AuthEntity $entity):void;

    public function resetPasswordViaEmail(AuthEntity $entity):void;

    public function activateAccountViaEmail(AuthEntity $entity):void;

    public function sendActivateCodeViaEmail(AuthEntity $entity):void;

    public function activateAccountViaSms(AuthEntity $entity):void;

    public function sendActivateCodeViaSms(AuthEntity $entity):void;
}

