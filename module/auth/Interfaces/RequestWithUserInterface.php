<?php


namespace Modules\Auth\Interfaces;


use CodeIgniter\HTTP\RequestInterface;

/**
 * Expected behavior of a Security.
 */
interface RequestWithUserInterface extends RequestInterface
{
    public function setUser(object $userData);

    public function getUserVar(string $key): string;

    public function getUser(): object;

}

