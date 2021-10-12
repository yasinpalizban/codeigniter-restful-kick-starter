<?php


namespace Modules\Common\Interfaces;

interface CustomFileInterface
{

    /**
     * removeSingleFile
     *
     *
     * @param string $path
     */
    public function removeSingleFile(string $path);
    /**
     * removeMultipleFiles
     *
     *
     * @param string $path
     */
    public function removeMultipleFiles( string $path);

    /**
     * get Error.
     * @return string
     */
    public function getError(): string;
}


