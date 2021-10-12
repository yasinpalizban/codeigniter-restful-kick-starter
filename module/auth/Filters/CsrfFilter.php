<?php

namespace Modules\Auth\Filters;

use Modules\Shared\Enums\FilterErrorType;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Config\Services;
use Modules\Auth\Libraries\CsrfSecurity;


/**
 * CSRF filter.
 *
 * This filter is not intended to be used from the command line.
 *
 * @codeCoverageIgnore
 */
class CsrfFilter implements FilterInterface
{

    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Authenticate. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     * @param array|null $arguments
     *
     * @return mixed
     * @throws \CodeIgniter\Security\Exceptions\SecurityException
     */
    public function before(RequestInterface $request, $arguments = null)
    {

        if ($request->isCLI()) {
            return;
        }
        $ruleRoute = \Modules\Auth\Config\Services::ruleRoute();
        if ($ruleRoute->ignoreRoute()) {
            return;
        }

        $csrfSecurity = new CsrfSecurity();

        if (!$csrfSecurity->verify($request)) {
            return Services::response()->setJSON(['success' => false,
                'type' => FilterErrorType::Csrf,

                'error' => lang('Authenticate.filter.csrf')])->setContentType('application/json')
                ->setStatusCode(Response::HTTP_UNAUTHORIZED, lang('Authenticate.filter.csrf'));

        }

    }

    //--------------------------------------------------------------------

    /**
     * We don't have anything to do here.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     * @param ResponseInterface|\CodeIgniter\HTTP\Response $response
     * @param array|null $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if ($request->isCLI()) {
            return;
        }

        $ruleRoute = \Modules\Auth\Config\Services::ruleRoute();
        if ($ruleRoute->ignoreRoute()) {
            return;
        }
        $csrfSecurity = new CsrfSecurity();

        $data = json_decode($response->getJSON(), true);


        if (!empty($data) && $csrfSecurity->refresh($request) == true) {
            $data['csrf'] = $csrfSecurity->getHash();
        }
        $response->setJSON($data);


    }


}
