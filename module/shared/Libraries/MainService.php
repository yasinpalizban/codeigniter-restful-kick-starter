<?php

namespace Modules\Shared\Libraries;

use CodeIgniter\HTTP\Exceptions\HTTPException;

class MainService
{

    protected function httpException(string $message, int $code, $body = null)
    {

        try {

            throw new HttpException($message, $code);

        } catch (\Exception $e) {

            header("HTTP/1.1 {$code} {$message}");
            $json = json_encode(['error' => $body]);
            echo $json;
        }

        exit();
    }

}