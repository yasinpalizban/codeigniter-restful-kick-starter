<?php namespace Modules\Auth\Controllers;


use Modules\Auth\Config\ModuleAuthConfig;
use Modules\Auth\Config\Services;
use Modules\Auth\Entities\AuthEntity;
use Modules\Auth\Enums\RoleType;
use Modules\Auth\Interfaces\AuthControllerInterface;
use Modules\Shared\Config\ModuleSharedConfig;
use Modules\Shared\Controllers\BaseController;
use Modules\Shared\Enums\NotificationType;

use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\AuthTrait;
use Pusher\Pusher;
use ReCaptcha\ReCaptcha;

class Auth extends BaseController implements AuthControllerInterface
{
    use AuthTrait;


    public function signInJwt(): ResponseInterface
    {


        /**
         * Attempts to verify the user's credentials
         * through a POST request.
         */
        $rules = [
            'login' => 'required',
            'password' => 'required'

        ];

        $config = config('Myth\Auth\Config\Auth');

        if ($config->validFields == ['email']) {
            $rules['login'] .= '|valid_email';
        };


        if (!$this->validate($rules)) {


            return $this->response->setJSON(['error' => $this->validator->getErrors()])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');
        }

        $authEntity = new AuthEntity((array)$this->request->getVar());

        $authEntity->logInMode()->loginDate()->ipAddress($this->request->getIPAddress());


        $authService = Services::authService();
        $data = $authService->signInJwt($authEntity);
        $authConfig = new  ModuleAuthConfig();

        return $this->response->setHeader($authConfig->jwt['name'], $data['jwt']['token'])
            ->setCookie($authConfig->jwt['name'], $data['jwt']['token'], $data['jwt']['expire'])->
            setJSON($data)->setStatusCode(ResponseInterface::HTTP_OK, lang('Authenticate.auth.logIn'))
            ->setContentType('application/json');


    }

    public function signIn(): ResponseInterface
    {


        $this->setupAuthClasses();


        /**
         * Attempts to verify the user's credentials
         * through a POST request.
         */
        $rules = [
            'login' => 'required',
            'password' => 'required'

        ];

        $config = config('Myth\Auth\Config\Auth');

        if ($config->validFields == ['email']) {
            $rules['login'] .= '|valid_email';
        };


        if (!$this->validate($rules)) {


            return $this->response->setJSON(['error' => $this->validator->getErrors()])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');
        }


        $authEntity = new AuthEntity((array)$this->request->getVar());
        $authEntity->logInMode();


        $remember = $authEntity->remember ?? false;

        if (!$this->authenticate->attempt([$authEntity->loginType => $authEntity->login, 'password' => $authEntity->password], $remember)) {


            return $this->response->setJSON(['error' => $this->authenticate->error(),
            ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, lang('Auth.badAttempt'))
                ->setContentType('application/json');

        }

        // Is the user being forced to reset their password?
        if ($this->authenticate->user()->force_pass_reset === true) {

            //  return redirect()->to(route_to('reset-password') . '?token=' . $this->auth->user()->reset_hash)->withCookies();
            return $this->response->setJSON(['token' => $this->authenticate->user()->reset_hash])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, lang('Authenticate.auth.foreResetPassword'))
                ->setContentType('application/json');

        }

        $authService = Services::authService();
        $data = $authService->signIn($authEntity);


        return $this->response->setJSON(
            $data
        )->setStatusCode(ResponseInterface::HTTP_OK, lang('Authenticate.auth.logIn'))
            ->setContentType('application/json');


    }


    /**
     * Log the user out.
     */
    public function signOut(): ResponseInterface
    {

        $this->setupAuthClasses();
        $authConfig = new  ModuleAuthConfig();

        $jwtHeader = $this->request->getServer('HTTP_AUTHORIZATION');
        $jwtCookie = $this->request->getCookie($authConfig->jwt['name']);


        if ($this->authenticate->check()) {
            $this->authenticate->logout();
        } else if (!is_null($jwtHeader) || !is_null($jwtCookie)) {


            $authService = Services::authService();

            $requestWithUser = Services::requestWithUser();
            $authService->signOutJwt($requestWithUser->getUser());

            $this->response->setHeader($authConfig->jwt['name'], '');
            $this->response->setCookie($authConfig->jwt['name'], '', 0);

        }

        return $this->response->setJSON(['success' => true])
            ->setStatusCode(ResponseInterface::HTTP_OK, lang('Authenticate.auth.logOut'))
            ->setContentType('application/json');


    }



    //--------------------------------------------------------------------
    // Register
    //--------------------------------------------------------------------

    /**
     * Displays the user registration page.
     */
    public function signUp(): ResponseInterface
    {



        $throttler = \Codeigniter\Config\Services::throttler();

        if ($throttler->check($this->request->getIPAddress(), 5, MINUTE) === false) {

            return $this->response->setJSON(['data' => $throttler->getTokentime()])
                ->setStatusCode(ResponseInterface::HTTP_TOO_MANY_REQUESTS, lang('Auth.tooManyRequests', [$throttler->getTokentime()]))
                ->setContentType('application/json');

        }
        
        // Validate here first, since some things,
        // like the password, can only be validated properly here.
        // strong password didint work custom validation  strong_password
        // password=> strong_password
        helper('authentication');

        if (loginVia($this->request->getVar('login') ?? $this->request->getVar('login')) == 'email') {

            $lineRule = 'required|valid_email|is_unique[users.email]';
        } else if (loginVia($this->request->getVar('login') ?? $this->request->getVar('login')) == 'phone') {

            $lineRule = 'required|min_length[9]|is_unique[users.phone]';
        } else {

            return $this->response->setJSON(['error' => lang('Authenticate.auth.emailOrPhone')])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');
        }

        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
            'login' => $lineRule,
            'password' => 'required|min_length[6]',
            'passConfirm' => 'required|matches[password]',
            'action' => 'required',
            'token' => 'required',
            'socialLogin' => 'required'

        ];

        if (!$this->validate($rules)) {

            return $this->response->setJSON([

                'error' => service('validation')->getErrors(),
            ])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');

        }


        $authConfig = new  \Modules\Auth\Config\ModuleAuthConfig();
        // ->setExpectedHostname($_SERVER['SERVER_NAME'])
        $recaptcha = new ReCaptcha($authConfig->captcha['secretKey']);
        $resp = $recaptcha->setExpectedAction($this->request->getVar('action'))
            ->setScoreThreshold(0.2)
            ->verify($this->request->getVar('token'), $_SERVER['REMOTE_ADDR']);
        // verify the response
        if (!$resp->isSuccess() && !$this->request->getVar('socialLogin')) {
            // spam submission
            // show error message
            return $this->response->setJSON([
                'error' => $resp->getErrorCodes()])
                ->setStatusCode(ResponseInterface:: HTTP_UNAUTHORIZED, lang('Authenticate.auth.captchaError'))
                ->setContentType('application/json');
        }
        $authEntity = new AuthEntity((array)$this->request->getVar());
        $authEntity->logInMode()->createdAt()->setRole(RoleType::Member);
        unset($authEntity->token);
        unset($authEntity->action);
        $authService = Services::authService();
        $authService->signUp($authEntity);


        $sharedConfig = new ModuleSharedConfig();

        $pusher = new Pusher(
            $sharedConfig->pusher['authKey'],
            $sharedConfig->pusher['secret'],
            $sharedConfig->pusher['appId'],
            ['cluster' => $sharedConfig->pusher['cluster'],
                'useTLS' => $sharedConfig->pusher['useTLS']]
        );
        $data['type'] = NotificationType::NewUser;
        $data['message'] = 'new user register';
        $data['counter'] = 1;
        $data['date'] = date('Y-m-d H:i:s', time());;
        $pusher->trigger('notification-channel', 'my-event', $data);


        return $this->response->setJSON(['success' => true])
            ->setStatusCode(ResponseInterface::HTTP_OK, lang('Auth.registerSuccess'))
            ->setContentType('application/json');


    }

//--------------------------------------------------------------------
// Forgot Password
//--------------------------------------------------------------------

    /**
     * Displays the forgot password form.
     */
    public
    function forgot(): ResponseInterface
    {


        $rules = [

            'login' => 'required',
            'action' => 'required',
            'token' => 'required',

        ];

        if (!$this->validate($rules)) {


            return $this->response->setJSON(['error' => service('validation')->getErrors()])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');

        }

        $authConfig = new  \Modules\Auth\Config\ModuleAuthConfig();
        // ->setExpectedHostname($_SERVER['SERVER_NAME'])
        $recaptcha = new ReCaptcha($authConfig->captcha['secretKey']);

        $resp = $recaptcha->setExpectedAction($this->request->getVar('action'))
            ->setScoreThreshold(0.2)
            ->verify($this->request->getVar('token'), $_SERVER['REMOTE_ADDR']);
        // verify the response
        if (!$resp->isSuccess()) {
            // spam submission
            // show error message
            return $this->response->setJSON([
                'error' => $resp->getErrorCodes()])
                ->setStatusCode(ResponseInterface:: HTTP_UNAUTHORIZED, lang('Authenticate.auth.captchaError'))
                ->setContentType('application/json');
        }


        $authEntity = new AuthEntity((array)$this->request->getVar());
        $authEntity->logInMode(false)->generateResetHash();
        unset($authEntity->token);
        unset($authEntity->action);
        $authService = Services::authService();
        $authService->forgot($authEntity);


        return $this->response->setJSON(['success' => true])
            ->setStatusCode(ResponseInterface::HTTP_OK, lang('Authenticate.auth.forgotEmailSmsSent'))
            ->setContentType('application/json');

    }

    /**
     * Displays the Reset Password form.
     */
    /**
     * Verifies the code with the email and saves the new password,
     * if they all pass validation.
     *
     * @return mixed
     */

    public
    function resetPasswordViaSms(): ResponseInterface
    {


        $rules = [
            'code' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'passConfirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {

            return $this->response->setJSON(['error' => $this->validator->getErrors(),])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');

        }

        $authEntity = new AuthEntity((array)$this->request->getVar());
        $authEntity->resetPassword();
        $authService = Services::authService();
        $authService->resetPasswordViaSms($authEntity);


        return $this->response->setJSON(['success' => true])->setStatusCode(ResponseInterface::HTTP_OK, lang('Auth.resetSuccess'))
            ->setContentType('application/json');


    }

    /**
     * Verifies the code with the email and saves the new password,
     * if they all pass validation.
     *
     * @return mixed
     */

    public
    function resetPasswordViaEmail(): ResponseInterface
    {


        $rules = [
            'token' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required',
            'passConfirm' => 'required|matches[password]',
        ];


        if (!$this->validate($rules)) {

            return $this->response->setJSON(['error' => $this->validator->getErrors()])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');
        }

        $authEntity = new AuthEntity((array)$this->request->getVar());
        $authEntity->resetPassword()
            ->userAgent($this->request->getUserAgent())
            ->ipAddress($this->request->getIPAddress());
        $authService = Services::authService();
        $authService->resetPasswordViaEmail($authEntity);

        return $this->response->setJSON(['success' => true])->setStatusCode(ResponseInterface::HTTP_OK, lang('Auth.resetSuccess'))
            ->setContentType('application/json');


    }

    /**
     * Activate account.
     *
     * @return mixed
     */
    public
    function activateAccountViaEmail(): ResponseInterface
    {
        $rules = [
            'token' => 'required',
            'email' => 'required|valid_email',
        ];
        if (!$this->validate($rules)) {

            return $this->response->setJSON([

                'error' => $this->validator->getErrors()])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');
        }




        $authEntity = new AuthEntity((array)$this->request->getVar());
        $authEntity->activate()
            ->userAgent($this->request->getUserAgent())
            ->ipAddress($this->request->getIPAddress());


        $authService = Services::authService();
        $authService->activateAccountViaEmail($authEntity);

        return $this->response->setJSON(['success' => true])
            ->setStatusCode(ResponseInterface::HTTP_OK, lang('Auth.registerSuccess'))
            ->setContentType('application/json');


    }

    /**
     * Resend activation account.
     *
     * @return mixed
     * @throws \Exception
     */
    public
    function sendActivateCodeViaEmail(): ResponseInterface
    {


        $rules = [
            'email' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => service('validation')->getErrors()
            ])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');
        }



        $authEntity = new AuthEntity((array)$this->request->getVar());
        $authEntity->generateActivateHash();
        $authService = Services::authService();
        $authService->sendActivateCodeViaEmail($authEntity);

        return $this->response->setJSON(['success' => true])
            ->setStatusCode(ResponseInterface::HTTP_OK, lang('Auth.activationSuccess'))
            ->setContentType('application/json');

    }

    /**
     * Activate account via sma.
     *
     * @return mixed
     */
    public
    function activateAccountViaSms(): ResponseInterface
    {

        $rules = [
            'phone' => 'required',
            'code' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => service('validation')->getErrors()])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');
        }


        $authEntity = new AuthEntity((array)$this->request->getVar());
        $authEntity->activate();
        $authService = Services::authService();
        $authService->activateAccountViaSms($authEntity);


        return $this->response->setJSON(['success' => true])
            ->setStatusCode(ResponseInterface::HTTP_OK, lang('Authenticate.auth.registerSuccess'))
            ->setContentType('application/json');

    }

    /**
     * Activate account via sma.
     *
     * @return mixed
     */
    public
    function sendActivateCodeViaSms(): ResponseInterface
    {

        $rules = [
            'phone' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['error' => service('validation')->getErrors()])
                ->setStatusCode(ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Authenticate.auth.validation'))
                ->setContentType('application/json');
        }

        $authEntity = new AuthEntity((array)$this->request->getVar());
        $authService = Services::authService();
        $authService->sendActivateCodeViaSms($authEntity);


        return $this->response->setJSON(['success' => true,
        ])
            ->setStatusCode(ResponseInterface::HTTP_OK, lang('Authenticate.auth.smsActivation'))
            ->setContentType('application/json');


    }


}