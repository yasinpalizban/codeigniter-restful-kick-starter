<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Modules\Auth\Interfaces;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Security\Exceptions\SecurityException;

/**
 * Expected behavior of a Security.
 */
interface CsrfInterface
{
    /**
     * CSRF Verify
     *
     * @param RequestInterface $request
     *
     * @return bool
     *
     * @throws SecurityException
     */
    public function verify(RequestInterface $request): bool;

    /**
     * Returns the CSRF Hash.
     *
     * @return string|null
     */
    public function getHash(): ?string;

    /**
     * Returns the CSRF Token Name.
     *
     * @return string
     */
    public function getTokenName(): string;

    /**
     * Returns the CSRF Header Name.
     *
     * @return string
     */
    public function getHeaderName(): string;

    /**
     * Returns the CSRF Cookie Name.
     *
     * @return string
     */
    public function getCookieName(): string;

    /**
     * Check if CSRF cookie is expired.
     *
     * @return boolean
     *
     * @deprecated
     */
    public function isExpired(): bool;

    /**
     * CSRF Verify
     *
     * @param RequestInterface $request
     *
     * @return $this|false
     *
     */
    public function refresh(RequestInterface $request): bool;

    /**
     * CSRF init
     *
     * @return string
     */
    public function init(): string;

}

