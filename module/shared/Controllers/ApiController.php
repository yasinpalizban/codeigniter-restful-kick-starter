<?php

namespace Modules\Shared\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Modules\Auth\Config\Services;
use Myth\Auth\AuthTrait;
use Psr\Log\LoggerInterface;
use  Modules\Shared\Interfaces\UrlQueryParamInterface;
use  Modules\Shared\Libraries\UrlQueryParam;

class ApiController extends ResourceController
{
    use AuthTrait;

    protected $format = "";
    public object $userObject;
    public UrlQueryParamInterface $urlQueryParam;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [
        'cookie',
        'url',
        'from',
        'filesystem',
        'text',
        'shared'
    ];

    /**
     * Constructor.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     */


    /**
     * @var string
     * Holds the session instance
     */
    protected $session;

    public function __construct()
    {

        $this->userObject = (object)[];
    }

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);


        $this->urlQueryParam = new UrlQueryParam($request);

        $requestWithUser = Services::requestWithUser();
        $this->userObject = $requestWithUser->getUser();

    }

}
