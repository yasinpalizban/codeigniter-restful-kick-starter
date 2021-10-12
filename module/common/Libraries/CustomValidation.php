<?php namespace Modules\Common\Libraries;

use CodeIgniter\HTTP\Response;
use Modules\Common\Interfaces\CustomValidationInterface;
use PHPUnit\Framework\MockObject\Api;

class CustomValidation implements  CustomValidationInterface
{

    private $request;

    public function __construct()
    {


    }

    public function isHasJson(string $filed): bool
    {
        $str = $this->request->getJSON($filed);
        if (is_object($str)) {
            return true;
        }

        return is_array($str) ? !empty($str) : (trim($str) !== '');
    }
}
