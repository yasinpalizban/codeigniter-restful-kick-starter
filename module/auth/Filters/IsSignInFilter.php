<?php namespace Modules\Auth\Filters;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Modules\Auth\Config\ModuleAuthConfig;

class IsSignInFilter implements FilterInterface
{
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $authenticate = \Myth\Auth\Config\Services::authentication();

        $response = \CodeIgniter\Config\Services::response();
        $authConfig = new  ModuleAuthConfig();

        $jwtHeader = $request->getServer('HTTP_AUTHORIZATION');
        $jwtCookie = $request->getCookie($authConfig->jwt['name']);

        if ($authenticate->check()) {


            return $response->setJSON(['success' => false])
                ->setStatusCode(ResponseInterface::HTTP_NOT_MODIFIED, lang('Authenticate.auth.loggedIn'))
                ->setContentType('application/json');


        } else if (!is_null($jwtHeader) && !is_null($jwtCookie)) {


            helper('jwt');

            if (!empty($jwtHeader))
                $jwtToken = getJWTHeader($jwtHeader);
            else
                $jwtToken = $jwtCookie;

            try {

                $jwtUser = validateJWT($jwtToken, $authConfig->jwt['secretKey']);


                return $response->setJSON(['success' => false])
                    ->setStatusCode(ResponseInterface::HTTP_NOT_MODIFIED, lang('Authenticate.auth.loggedIn'))
                    ->setContentType('application/json');


            } catch (\Exception $e) {

            }


        }


    }


}
