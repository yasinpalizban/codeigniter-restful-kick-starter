<?php


namespace Modules\Common\Interfaces;

interface CustomValidationInterface
{


    /**
     * get Error.
     * @param string $field
     * @return bool
     */
    public function isHasJson(string  $field): bool;
}


