<?php

namespace Modules\Auth\Libraries;

use CodeIgniter\Cookie\Cookie;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Security\Exceptions\SecurityException;
use CodeIgniter\Session\SessionInterface;
use Config\Cookie as CookieConfig;
use Config\Security as SecurityConfig;
use Modules\Auth\Interfaces\CsrfInterface;

/**
 * Class Security
 *
 * Provides methods that help protect your site against
 * Cross-Site Request Forgery attacks.
 */
class CsrfSecurity implements CsrfInterface
{
    /**
     *
     * Holds the session instance
     */
    protected SessionInterface $session;


    /**
     * CSRF Hash
     *
     * Random hash for Cross Site Request Forgery protection cookie
     *
     * @var string|null
     */
    protected $hash = null;

    /**
     * CSRF Token Name
     *
     * Token name for Cross Site Request Forgery protection cookie.
     *
     * @var string
     */
    protected $tokenName = 'csrf_token_name';

    /**
     * CSRF Header Name
     *
     * Token name for Cross Site Request Forgery protection cookie.
     *
     * @var string
     */
    protected $headerName = 'X-CSRF-TOKEN';

    /**
     * The CSRF Cookie instance.
     *
     * @var Cookie
     */
    protected $cookie;

    /**
     * CSRF Cookie Name
     *
     * Cookie name for Cross Site Request Forgery protection cookie.
     *
     * @var string
     */
    protected $cookieName = 'csrf_cookie_name';

    /**
     * EX CSRF Cookie Name
     *
     * Cookie name for Cross Site Request Forgery protection cookie.
     *
     * @var string
     */
    protected $exCookieName = 'ex_csrf_cookie_name';


    /**
     * CSRF Expires
     *
     * Expiration time for Cross Site Request Forgery protection cookie.
     *
     * Defaults to two hours (in seconds).
     *
     * @var integer
     *
     * @deprecated
     */
    protected $expires = 7200;

    /**
     * CSRF Regenerate
     *
     * Regenerate CSRF Token on every request.
     *
     * @var boolean
     */
    protected $regenerate = true;

    /**
     * CSRF Redirect
     *
     * Redirect to previous page with error on failure.
     *
     * @var boolean
     */


    /**
     * CSRF SameSite
     *
     * Setting for CSRF SameSite cookie token.
     *
     * Allowed values are: None - Lax - Strict - ''.
     *
     * Defaults to `Lax` as recommended in this link:
     *
     * @see https://portswigger.net/web-security/csrf/samesite-cookies
     *
     * @var string
     *
     * @deprecated
     */
    protected $samesite = Cookie::SAMESITE_LAX;

    /**
     * Constructor.
     *
     * Stores our configuration and fires off the init() method to setup
     * initial state.
     *
     *
     */
    public function __construct()
    {

        /** @var SecurityConfig */

        $this->session = \Codeigniter\Config\Services::session();

        // Store CSRF-related configurations

        /** @var CookieConfig */
        $cookie = config('Cookie');


        $this->cookieName = $cookie->prefix . $this->cookieName;

        $expires =  7200;

        Cookie::setDefaults($cookie);
        $this->cookie = new Cookie($this->cookieName, $this->generateHash(), [
            'expires' => $expires === 0 ? 0 : time() + $expires,
        ]);
    }


    /**
     * CSRF Verify
     *
     * @param RequestInterface $request
     *
     * @return $this|false
     *
     * @throws SecurityException
     */
    public function verify(RequestInterface $request): bool
    {


        $exCsrfCookie = null;
        $csrfCookie = null;
        $csrfHeader = null;

        if ($this->session->has($this->cookieName)) {
            $csrfCookie = $this->session->get($this->cookieName);
        } else {
            $csrfCookie = get_cookie($this->cookieName);
        }
        if ($this->session->has($this->exCookieName)) {
            $exCsrfCookie = $this->session->get($this->exCookieName);
        }

        $csrfHeader = $request->getHeaderLine($this->headerName);

        log_message('info', 'CSRF token verifying.');
        return ($csrfHeader == $csrfCookie || $csrfHeader == $exCsrfCookie);


    }


    /**
     * CSRF Generate
     *
     * @param RequestInterface $request
     *
     *
     * @return array
     *

     */

    public function refresh(RequestInterface $request): bool
    {

        if ($this->session->has($this->exCookieName) &&
            ($request->getHeaderLine($this->headerName) ==
                $this->session->get($this->exCookieName))) {
            return false;
        }

        if ($this->session->has($this->cookieName)) {
            $exCsrf = $this->session->get($this->cookieName);
            $this->session->set($this->exCookieName, $exCsrf);
        }

        if ($this->regenerate) {
            $this->hash = null;
            unset($_COOKIE[$this->cookieName]);
        }
        $newToken = $this->generateHash();
        $this->session->set($this->cookieName, $newToken);
        $this->cookie = $this->cookie->withValue($newToken);
        $this->sendCookie($request);

        log_message('info', 'CSRF token generate');
        return true;
    }


    /**
     * Returns the CSRF Hash.
     *
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * Returns the CSRF Token Name.
     *
     * @return string
     */
    public function getTokenName(): string
    {
        return $this->tokenName;
    }

    /**
     * Returns the CSRF Header Name.
     *
     * @return string
     */
    public function getHeaderName(): string
    {
        return $this->headerName;
    }

    /**
     * Returns the CSRF Cookie Name.
     *
     * @return string
     */
    public function getCookieName(): string
    {
        return $this->cookieName;
    }

    /**
     * Check if CSRF cookie is expired.
     *
     * @return boolean
     *
     * @deprecated
     *
     * @codeCoverageIgnore
     */
    public function isExpired(): bool
    {
        return $this->cookie->isExpired();
    }


    /**
     * Generates the CSRF Hash.
     *
     * @return string
     */
    protected function generateHash(): string
    {
        if (is_null($this->hash)) {
            // If the cookie exists we will use its value.
            // We don't necessarily want to regenerate it with
            // each page load since a page could contain embedded
            // sub-pages causing this feature to fail
            if (isset($_COOKIE[$this->cookieName])
                && is_string($_COOKIE[$this->cookieName])
                && preg_match('#^[0-9a-f]{32}$#iS', $_COOKIE[$this->cookieName]) === 1
            ) {
                return $this->hash = $_COOKIE[$this->cookieName];
            }

            $this->hash = bin2hex(random_bytes(16));
        }

        return $this->hash;
    }

    /**
     * CSRF Send Cookie
     *
     * @param RequestInterface $request
     *
     * @return Security|false
     */
    protected function sendCookie(RequestInterface $request)
    {
        if ($this->cookie->isSecure() && !$request->isSecure()) {
            return false;
        }

        $this->doSendCookie();
        log_message('info', 'CSRF cookie sent.');

        return $this;
    }

    /**
     * Actual dispatching of cookies.
     * Extracted for this to be unit tested.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    protected function doSendCookie(): void
    {
        cookies([$this->cookie], false)->dispatch();
    }


    /**
     * Returns the CSRF Token Name.
     *
     * @return string
     *
     * @deprecated Use `CodeIgniter\Security\Security::getTokenName()` instead of using this method.
     *
     * @codeCoverageIgnore
     */
    public function getCSRFTokenName(): string
    {
        return $this->getTokenName();
    }

    /**
     *  init csrf
     *
     * @return string
     */
    public function init(): string
    {
        unset($_COOKIE[$this->cookieName]);
        $newToken = $this->generateHash();
        $this->session->set($this->cookieName, $newToken);
        $this->cookie = $this->cookie->withValue($newToken);
        log_message('info', 'CSRF init');
        return $newToken;
    }
}
