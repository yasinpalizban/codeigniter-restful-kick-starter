<?php


namespace Modules\Shared\Interfaces;

use Modules\Shared\Libraries\UrlQueryParam;

interface UrlQueryParamInterface
{

    //--------------------------------------------------------------------

    /**
     * get key
     *
     * @param array $dataMap
     *
     */

    public function dataMap(array $dataMap): void;


    //--------------------------------------------------------------------

    /**
     * Get value.
     *
     *
     * @param string $key
     * @param string $value
     * @param string $function
     * @param string $sign
     * @param string|null $joinWith
     * @return string
     */
    public function encodeQueryParam(string $key, string $value, string $function, string $sign, string $joinWith = ''): string;

    //--------------------------------------------------------------------


    /**
     * Get value.
     *
     *
     *
     * @return UrlQueryParam
     *
     */
    public function decodeQueryParam(): UrlQueryParam;


    /**
     * Get value.
     * @param string $append
     *
     */
    public function setTableName(string $append): UrlQueryParam;

    /**
     * Get value.
     * @return int
     */
    public function getForeignKey(): int;

    /**
     * Get value.
     * @param array|null $defaultPipeLine
     * @return array
     */
    public function getPipeLine(?array $defaultPipeLine = null): array;
}
