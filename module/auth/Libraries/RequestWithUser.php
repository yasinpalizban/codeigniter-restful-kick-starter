<?php

namespace Modules\Auth\Libraries;

use CodeIgniter\HTTP\IncomingRequest;
use Modules\Auth\Interfaces\RequestWithUserInterface;


class  RequestWithUser extends IncomingRequest implements RequestWithUserInterface
{
    protected object $userData;
    protected array $serviceEvent;

    public function __construct($config, $uri, $body, $userAgent)
    {
        parent::__construct($config, $uri, $body, $userAgent);
        $this->userData = (object)[];
    }

    public function setUser(object $userData)
    {
        $this->userData = $userData;
    }

    public function getUser(): object
    {
        return $this->userData;
    }

    public function getUserVar(string $key): string
    {
        if (isset($this->userData->$key)) {
            return $this->userData->$key;
        }

        return '';

    }


}