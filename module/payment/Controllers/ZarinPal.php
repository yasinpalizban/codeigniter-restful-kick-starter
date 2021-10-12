<?php namespace Modules\Payment\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Payment\Libraries\ZarinPayment;
use Modules\Shared\Controllers\ApiController;


class   ZarinPal extends ApiController
{
    /**
     * index function
     * @method : GET
     */
    public function index()
    {
        $zarinPal = new ZarinPayment();
        $zarinPal->paymentValidation();

        if (!$zarinPal->isHasError()) {
            return $this->respond([
                'success' => true,
            ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));

        } else {
            return $this->respond([
                'error' => $zarinPal->getErrors(),
                'success' => false,
            ], ResponseInterface::HTTP_BAD_REQUEST, lang('Shared.api.reject'));

        }


    }


    public function create()
    {

        $zarinPal = new ZarinPayment();
        $zarinPal->paymentGate();

        if (!$zarinPal->isHasError()) {
            return $this->respond([
                'success' => true,
            ], ResponseInterface::HTTP_OK, lang('Shared.api.save'));

        } else {
            return $this->respond([
                'error' => $zarinPal->getErrors(),
                'success' => false,
            ], ResponseInterface::HTTP_BAD_REQUEST, lang('Shared.api.reject'));

        }
    }
}
