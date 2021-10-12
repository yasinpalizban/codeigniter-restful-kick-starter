<?php namespace Modules\Common\Libraries;

use CodeIgniter\HTTP\Response;

class  CustomFile
{
    protected string $error;

    public function __construct()
    {
        $this->error = '';
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    public function removeSingleFile( $path)
    {
        try {

            if (file_exists($path)) {
                unlink($path);
            }


        } catch (\Exception $e) {
            $this->error = $e->getMessage();

        }

    }


    public function removeMultipleFiles( $path)
    {
        try {

            if (file_exists($path)) {
                unlink($path);
            }


        } catch (\Exception $e) {
            $this->error = $e->getMessage();

        }

    }


}
