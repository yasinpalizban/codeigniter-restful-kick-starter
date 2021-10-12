<?php

namespace Modules\Auth\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Modules\Shared\Enums\FilterErrorType;

class ThrottleFilter implements FilterInterface
{
    /**
     * This is a demo implementation of using the Throttler class
     * to implement rate limiting for your application.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     * @param array|null $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();

        // Restrict an IP address to no more
        // than 1 request per second across the
        // entire site.
        if ($throttler->check($request->getIPAddress(), 60, MINUTE) === false) {
            return \CodeIgniter\Config\Services::response()->setJSON(['success' => false,
                'type' => FilterErrorType::Throttle,
                'error' => lang('Authenticate.filter.throttle')])->setContentType('application/json')
                ->setStatusCode(Response::HTTP_TOO_MANY_REQUESTS, lang('Authenticate.filter.throttle'));

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
        // ...
    }
}
