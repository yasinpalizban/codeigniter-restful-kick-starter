<?php namespace Modules\Auth\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Authorization\PermissionModel;
use Myth\Auth\Entities\User;
use Myth\Auth\Password;

class  AuthEntity extends Entity
{

    protected $id;
    protected $phone;
    protected $username;
    protected $image;
    protected $email;
    protected $password;
    protected $login;
    protected $loginType;
    protected $remember;
    protected $IpAddress;
    protected $userAgent;
    protected $role;
    protected $code;
    protected $token;
    protected $action;


    protected $attributes = [
        'id' => null,
        'username' => null,
        'phone' => null,
        'email' => null,
        'active' => null,
        'created_at' => null,
        'login' => null,
        'loginType' => null,
        'password' => null,
        'ip_address' => null,
        'date' => null,
        'success' => null,
        'role' => null,
        'code' => null,
        'token' => null,
        'user_agent' => null,
        'remember' => null,

    ];
    protected $datamap = [

        'createdAt' => 'created_at',
        'ipAddress' => 'ip_address',
        'userAgent' => 'user_agent',

    ];


    /**
     * Define properties that are automatically converted to Time instances.
     */
    protected $dates = ['reset_at', 'reset_expires', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [
        'active' => 'boolean',
        'force_pass_reset' => 'boolean',
    ];

    /**
     * Per-user permissions cache
     * @var array
     */
    protected $permissions = [];

    /**
     * Per-user roles cache
     * @var array
     */
    protected $roles = [];

    /**
     * Automatically hashes the password when set.
     *
     * @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
     *
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->attributes['password'] = $password;
        $this->attributes['password_hash'] = Password::hash($password);

        /*
            Set these vars to null in case a reset password was asked.
            Scenario:
                user (a *dumb* one with short memory) requests a
                reset-token and then does nothing => asks the
                administrator to reset his password.
            User would have a new password but still anyone with the
            reset-token would be able to change the password.
        */
        $this->attributes['reset_hash'] = null;
        $this->attributes['reset_at'] = null;
        $this->attributes['reset_expires'] = null;
    }

    /**
     * Force a user to reset their password on next page refresh
     * or login. Checked in the LocalAuthenticator's check() method.
     *
     * @return $this
     * @throws \Exception
     *
     */
    public function forcePasswordReset(): AuthEntity
    {
        $this->generateResetHash();
        $this->attributes['force_pass_reset'] = 1;

        return $this;
    }

    /**
     * Generates a secure hash to use for password reset purposes,
     * saves it to the instance.
     *
     * @return $this
     * @throws \Exception
     */
    public function generateResetHash(): AuthEntity
    {
        $this->attributes['reset_hash'] = bin2hex(random_bytes(16));
        $this->attributes['reset_expires'] = date('Y-m-d H:i:s', time() + config('Auth')->resetTime);

        return $this;
    }

    /**
     * Generates a secure random hash to use for account activation.
     *
     * @return $this
     * @throws \Exception
     */
    public function generateActivateHash(): AuthEntity
    {
        $this->attributes['activate_hash'] = bin2hex(random_bytes(16));

        return $this;
    }

    /**
     * Activate user.
     *
     * @return $this
     */
    public function activate(): AuthEntity
    {
        $this->attributes['active'] = 1;
        $this->attributes['activate_hash'] = null;

        return $this;
    }

    /**
     * Unactivate user.
     *
     * @return $this
     */
    public function deactivate(): AuthEntity
    {
        $this->attributes['active'] = 0;

        return $this;
    }

    /**
     * Checks to see if a user is active.
     *
     * @return bool
     */
    public function isActivated(): bool
    {
        return isset($this->attributes['active']) && $this->attributes['active'] == true;
    }


    public function logInMode($flag = true): AuthEntity
    {
// Determine credential type

        if ($flag == false) {

            if (filter_var($this->attributes['login'], FILTER_VALIDATE_EMAIL)) {
                $this->attributes['loginType'] = 'email';

            } else if (is_numeric($this->attributes['login'])) {
                $this->attributes['loginType'] = 'phone';

            } else {
                $this->attributes['loginType'] = 'username';

            }

        } else {


            if (filter_var($this->attributes['login'], FILTER_VALIDATE_EMAIL)) {
                $this->attributes['loginType'] = 'email';
                $this->attributes['email'] = $this->attributes['login'];
            } else if (is_numeric($this->attributes['login'])) {
                $this->attributes['loginType'] = 'phone';
                $this->attributes['phone'] = $this->attributes['login'];

            } else {
                $this->attributes['loginType'] = 'username';
                $this->attributes['username'] = $this->attributes['login'];
            }
        }
        return $this;
    }

    public function ipAddress(string $ip): AuthEntity
    {
        $this->attributes['ip_address'] = $ip;
        return $this;
    }

    public function loginDate(): AuthEntity
    {
        $this->attributes['date'] = date('Y-m-d H:i:s', time());;
        return $this;
    }

    public function loginSuccess(bool $flag): AuthEntity
    {
        $this->attributes['success'] = $flag;
        return $this;
    }

    public function createdAt(): AuthEntity
    {
        $this->attributes['created_at'] = date('Y-m-d H:i:s', time());;
        return $this;
    }

    public function setRole(string $role): AuthEntity
    {
        $this->attributes['role'] = $role;
        return $this;
    }

    public function resetPassword(): AuthEntity
    {
        $this->attributes['reset_hash'] = null;
        $this->attributes['reset_expires'] = null;
        $this->attributes['reset_at'] = date('Y-m-d H:i:s');
        $this->attributes['force_pass_reset'] = false;


        return $this;
    }

    public function userAgent(string $agent): AuthEntity
    {
        $this->attributes['user_agent'] = $agent;
        return $this;
    }
}
