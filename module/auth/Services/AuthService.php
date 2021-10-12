<?php namespace Modules\Auth\Services;


use Codeigniter\Config\Services;
use CodeIgniter\Session\SessionInterface;
use Config\Email;
use Modules\Auth\Config\ModuleAuthConfig;
use Modules\Auth\Entities\AuthEntity;
use Modules\Auth\Interfaces\AuthServiceInterface;
use Modules\Auth\Models\GroupsPermissionModel;
use Modules\Auth\Models\UsersPermissionModel;
use Modules\Shared\Config\ModuleSharedConfig;
use Modules\Shared\Libraries\MainService;
use Modules\Shared\Libraries\Sms;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Auth\Libraries\CsrfSecurity;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Authorization\PermissionModel;
use Myth\Auth\AuthTrait;


use Myth\Auth\Models\LoginModel;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Password;


class AuthService extends MainService implements AuthServiceInterface
{
    use AuthTrait;

    private CsrfSecurity $csrfSecurity;
    private GroupModel $groupModel;
    private PermissionModel $permissionModel;
    private ModuleAuthConfig $authConfig;
    private UserModel $userModel;
    private SessionInterface $session;
    private LoginModel $loginModel;
    private ModuleSharedConfig $sharedConfig;
    private Sms $sms;
    private GroupsPermissionModel $groupsPermissionModel;
    private UsersPermissionModel $usersPermissionModel;

    private \CodeIgniter\Email\Email $email;


    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->authConfig = new  ModuleAuthConfig();
        $this->groupModel = new GroupModel();
        $this->csrfSecurity = new CsrfSecurity();
        $this->permissionModel = new PermissionModel();
        $this->loginModel = new LoginModel();
        $this->session = \Config\Services::session();
        $this->sharedConfig = new ModuleSharedConfig();
        $this->sms = new Sms($this->sharedConfig->sms['userName'],
            $this->sharedConfig->sms['password'],
            0);
        $this->groupsPermissionModel = new GroupsPermissionModel();
        $this->usersPermissionModel = new UsersPermissionModel();

        $this->email = Services::email();
        $emailConfig = new Email();
        $this->email->initialize($emailConfig)->
        setFrom($emailConfig->fromEmail, getenv('siteAddress'));
    }

    public function signInJwt(AuthEntity $entity): array
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_CONFLICT);


        $findUser = $this->userModel->asObject()->where($entity->loginType, $entity->login)->first();

        if (!$findUser) {
            $entity->loginSuccess(false);
            $this->loginModel->save($entity);
            $this->httpException(lang('Authenticate.auth.accountNotExist'), ResponseInterface::HTTP_CONFLICT);
        }
        if ($findUser->active == false) {
            $entity->loginSuccess(false);
            $this->loginModel->save($entity);
            $this->httpException(lang('Authenticate.auth.accountNotActive'), ResponseInterface::HTTP_CONFLICT);

        }
        if ($findUser->status == true) {
            $entity->loginSuccess(false);
            $this->loginModel->save($entity);
            $this->httpException(lang('Authenticate.auth.accountIsBand'), ResponseInterface::HTTP_CONFLICT);

        }

        if (!Password::verify($entity->password, $findUser->password_hash)) {
            $entity->loginSuccess(false);
            $this->loginModel->save($entity);
            $this->httpException(lang('Authenticate.auth.accountNotExist'), ResponseInterface::HTTP_CONFLICT);

        }

        $entity->loginSuccess(true);
       $this->loginModel->save($entity);

        $role = $this->groupModel->asObject()->getGroupsForUser($findUser->id);

        $permissions = $this->permissionModel->asObject()->where('active', '1')->findAll();
        $permissionUser = $this->usersPermissionModel->permissionsOfUser($findUser->id);
        $permissionGroup = $this->groupsPermissionModel->permissionsOfGroup($findUser->id);

        helper('jwt');

        $timeJwt = isset($entity->remember) ? timeJwt(true) : timeJwt(false);

        $jwtToken = generateJWT($findUser->id, $timeJwt['init'], $timeJwt['expire'], $this->authConfig->jwt['secretKey']);

        $data = [
            'success' => true,
            'role' => [
                'name' => $role[0]['name'],
                'id' => $role[0]['group_id']
            ],
            'permissions' => $permissions,
            'permissionUser' => $permissionUser,
            'permissionGroup' => $permissionGroup,
            'userInformation' => [
                'id' => $findUser->id,
                'userName' => $findUser->username,
                'image' => $findUser->image,
                'firstName' => $findUser->first_name,
                'lastName' => $findUser->last_name,
                'email' => $findUser->email,
                'phone' => $findUser->phone,
            ],
            'csrf' => $this->csrfSecurity->init(),
            'jwt' => [
                "token" => $jwtToken,
                "expire" => $timeJwt['expire'],
            ],

        ];

        return $data;


    }

    public function signIn(AuthEntity $entity): array
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_CONFLICT);

        helper('cookie');


//
        $findUser = $this->userModel->asObject()->where($entity->loginType, $entity->login)->first();

        $role = $this->groupModel->asObject()->getGroupsForUser($findUser->id);
        $permissions = $this->permissionModel->asObject()->where('active', '1')->findAll();
        $permissionUser = $this->usersPermissionModel->permissionsOfUser($findUser->id);
        $permissionGroup = $this->groupsPermissionModel->permissionsOfGroup($findUser->id);

        // store user inof in session
//        $this->session->set('userInformation', [
//            'userName' => $this->authenticate->user()->username,
//            'image' => $this->authenticate->user()->image,
//            'firstName' => $this->authenticate->user()->first_name,
//            'lastName' => $this->authenticate->user()->last_name,
//            'email' => $this->authenticate->user()->email,
//            'phone' => $this->authenticate->user()->phone,
//        ]);


        $data = [
            'success' => true,
            'role' => [
                'name' => $role[0]['name'],
                'id' => $role[0]['group_id']
            ],
            'permissions' => $permissions,
            'permissionUser' => $permissionUser,
            'permissionGroup' => $permissionGroup,
            'userInformation' => [
                'id' => $findUser->id,
                'userName' => $findUser->username,
                'image' => $findUser->image,
                'firstName' => $findUser->first_name,
                'lastName' => $findUser->last_name,
                'email' => $findUser->email,
                'phone' => $findUser->phone,
            ],
            'csrf' => $this->csrfSecurity->init(),
        ];

        return $data;

    }


    /**
     * Log the user out.
     * @param object $userData
     */
    public function signOutJwt(object $userData): void
    {
        if (!isset($userData->id)) $this->httpException(lang('Authenticate.filter.jwt'), ResponseInterface::HTTP_CONFLICT);


        $findUser = $this->userModel->asObject()->where("id", $userData->id)->first();

        if (is_null($findUser)) $this->httpException(lang('Auth.forgotNoUser'), ResponseInterface::HTTP_UNAUTHORIZED);


    }



    //--------------------------------------------------------------------
    // Register
    //--------------------------------------------------------------------

    /**
     * Displays the user registration page.
     * @param AuthEntity $entity
     */
    public function signUp(AuthEntity $entity): void
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_CONFLICT);

        $entity->generateActivateHash();

        $this->userModel->withGroup($entity->role);

        if (!$this->userModel->save($entity)) {


            $message = lang('Authenticate.auth.rejectRegistration') . " , " . serializeMessages($this->userModel->errors());
            $this->httpException($message, ResponseInterface::HTTP_BAD_REQUEST);

        }


        if ($entity->loginType == 'email') {


            $isSent = $this->email
                ->setTo($entity->email)
                ->setSubject(lang('Auth.activationSubject'))
                ->setMessage(view($this->authConfig->views['emailActivation'],
                    ['hash' => $entity->toArray()['activate_hash']]))
                ->setMailType('html')->send();


            if (!$isSent) {
                $this->groupModel->removeUserFromAllGroups($this->userModel->getInsertID());
                $this->userModel->where('id', $this->userModel->getInsertID())->delete();


                $message = lang('Authenticate.auth.failRegistration') . " , " . serializeMessages($this->activator->error() ?? lang('Auth.unknownError'));
                $this->httpException($message,
                    ResponseInterface::HTTP_BAD_REQUEST, $this->email->printDebugger(['headers']));

            }
        } else if ($entity->loginType == 'phone') {


            $isSend = $this->sms->sendActivationCode($entity->phone, getenv('siteAddress'));

            if ($isSend < 2000) {

                $message = lang('Authenticate.auth.smsSendFail');
                $this->httpException($message, ResponseInterface::HTTP_FOUND);

            }
        }


    }

//--------------------------------------------------------------------
// Forgot Password
//--------------------------------------------------------------------

    /**
     * Displays the forgot password form.
     * @param AuthEntity $entity
     */
    public
    function forgot(AuthEntity $entity): void
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_CONFLICT);


        $findUser = $this->userModel->where($entity->loginType, $entity->login)->first();

        if (is_null($findUser)) $this->httpException(lang('Auth.forgotNoUser'), ResponseInterface::HTTP_UNAUTHORIZED);

        $statusEmail = false;
        $statusSms = 0;
        if (!is_null($findUser->email)) {

            $statusEmail = $this->email
                ->setTo($findUser->email)
                ->setSubject(lang('Auth.forgotSubject'))
                ->setMessage(view($this->authConfig->views['emailForgot'],
                    ['hash' => $entity->toArray()['reset_hash']]))
                ->setMailType('html')->send();


        }


        if (!is_null($findUser->phone)) {

            $statusSms = $this->sms->sendActivationCode($findUser->phone, getenv('siteAddress'));

        }


        if ($statusSms < 2000 && !$statusEmail) $this->httpException(lang('Auth.unknownError'),
            ResponseInterface::HTTP_BAD_REQUEST, $this->email->printDebugger(['headers']));


        if (!$this->userModel->update($findUser->id, $entity)) {


            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->userModel->errors()));

        }


    }


    /**
     * Verifies the code with the email and saves the new password,
     * if they all pass validation.
     *
     * @param AuthEntity $entity
     */

    public
    function resetPasswordViaSms(AuthEntity $entity): void
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_ACCEPTABLE);


        $findUser = $this->userModel->where('phone', $entity->phone)->first();;


        if (is_null($findUser)) $this->httpException(lang('Authenticate.forgotNoUserPhone'), ResponseInterface::HTTP_CONFLICT);


        if (!$this->sms->isActivationCodeValid($entity->phone, $entity->code)) $this->httpException(lang('Auth.resetTokenExpired'), ResponseInterface::HTTP_NOT_ACCEPTABLE);


        unset($entity->phone);
        if (!$this->userModel->update($findUser->id, $entity)) {


            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->userModel->errors()));

        }


    }

    /**
     * Verifies the code with the email and saves the new password,
     * if they all pass validation.
     *
     * @param AuthEntity $entity
     */

    public
    function resetPasswordViaEmail(AuthEntity $entity): void
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_ACCEPTABLE);


        $this->userModel->logResetAttempt(
            $entity->email,
            $entity->token,
            $entity->ip_address,
            $entity->user_agent
        );


        $findUser = $this->userModel->where('email', $entity->email)
            ->where('reset_hash', $entity->token)->first();


        if (is_null($findUser)) $this->httpException(lang('Auth.forgotNoUser'), ResponseInterface::HTTP_CONFLICT);


        // Reset token still valid?
        if (!empty($findUser->reset_expires) && time() > $findUser->reset_expires->getTimestamp()) $this->httpException(lang('Auth.resetTokenExpired'), ResponseInterface::HTTP_NOT_ACCEPTABLE);

        unset($entity->email);
        if (!$this->userModel->update($findUser->id, $entity)) {

            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->userModel->errors()));

        }

    }

    /**
     * Activate account.
     *
     * @param AuthEntity $entity
     */
    public
    function activateAccountViaEmail(AuthEntity $entity): void
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_ACCEPTABLE);

        $this->userModel->logActivationAttempt(
            $entity->token,
            $entity->ip_address,
            $entity->user_agent
        );


        $findUser = $this->userModel->where('activate_hash', $entity->token)
            ->where('active', 0)->where('email', $entity->email)
            ->first();
        if (is_null($findUser)) $this->httpException(lang('Auth.activationNoUser'), ResponseInterface::HTTP_CONFLICT);

        unset($entity->email);
        if (!$this->userModel->update($findUser->id, $entity)) {

            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->userModel->errors()));

        }

    }

    /**
     * Resend activation account.
     * @param AuthEntity $entity
     */
    public
    function sendActivateCodeViaEmail(AuthEntity $entity): void
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_ACCEPTABLE);

        $findUser = $this->userModel->where('email', $entity->email)
            ->where('active', 0)
            ->first();

        if (is_null($findUser)) $this->httpException(lang('Auth.activationNoUser'), ResponseInterface::HTTP_CONFLICT);


        $isSent = $this->email
            ->setTo($entity->email)
            ->setSubject(lang('Auth.activationSubject'))
            ->setMessage(view($this->authConfig->views['emailActivation'],
                ['hash' => $entity->toArray()['activate_hash']]))
            ->setMailType('html')->send();

        if (!$isSent) {


            $this->httpException(lang('Auth.unknownError'),
                ResponseInterface::HTTP_BAD_REQUEST, $this->email->printDebugger(['headers']['headers'] ?? lang('Auth.unknownError')));

        }
        unset($entity->email);


        if (!$this->userModel->update($findUser->id, $entity)) {


            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->userModel->errors()));

        }

    }

    /**
     * Activate account via sma.
     *
     * @param AuthEntity $entity
     */
    public function activateAccountViaSms(AuthEntity $entity): void
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_ACCEPTABLE);

        $result = $this->sms->isActivationCodeValid($entity->phone,
            $entity->code);

        if ($result == false) $this->httpException(lang('Authenticate.auth.smsActivationFail'), ResponseInterface::HTTP_CONFLICT);

        $findUser = $this->userModel->where('active', 0)
            ->where('phone', $entity->phone)->first();

        if (is_null($findUser)) $this->httpException(lang('Auth.activationNoUser'), ResponseInterface::HTTP_CONFLICT);

        unset($entity->phone);
        if (!$this->userModel->update($findUser->id, $entity)) {

            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->userModel->errors()));

        }
    }

    /**
     * Activate account via sma.
     *
     * @param AuthEntity $entity
     */
    public
    function sendActivateCodeViaSms(AuthEntity $entity): void
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_ACCEPTABLE);

        $findUser = $this->userModel
            ->where('active', 0)->where('phone', $entity->phone)->first();
        if (is_null($findUser)) $this->httpException(lang('Auth.activationNoUser'), ResponseInterface::HTTP_CONFLICT);

        $result = $this->sms->sendActivationCode($entity->phone, getenv('siteAddress'));
        if ($result < 2000) $this->httpException(lang('Authenticate.auth.smsSendFail'), ResponseInterface::HTTP_BAD_REQUEST);


    }


}
