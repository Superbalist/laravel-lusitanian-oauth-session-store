<?php

namespace Superbalist\LusitanianOAuth;

use Illuminate\Session\Store;
use OAuth\Common\Storage\Exception\AuthorizationStateNotFoundException;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Token\TokenInterface;

class LaravelTokenSessionStore implements TokenStorageInterface
{
    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * @var string
     */
    protected $sessionVariableName;

    /**
     * @var string
     */
    protected $stateVariableName;

    /**
     * @param Store $session
     * @param string $sessionVariableName
     * @param string $stateVariableName
     */
    public function __construct(
        Store $session,
        $sessionVariableName = 'lusitanian_oauth_token',
        $stateVariableName = 'lusitanian_oauth_token'
    ) {
        $this->session = $session;
        $this->sessionVariableName = $sessionVariableName;
        $this->stateVariableName = $stateVariableName;

        if (!$this->session->has($this->sessionVariableName)) {
            $this->session->put($this->sessionVariableName, []);
        }

        if (!$this->session->has($this->stateVariableName)) {
            $this->session->put($this->stateVariableName, []);
        }
    }

    /**
     * @param string $service
     *
     * @throws \OAuth\Common\Storage\Exception\TokenNotFoundException
     *
     * @return TokenInterface
     */
    public function retrieveAccessToken($service)
    {
        if ($this->hasAccessToken($service)) {
            return $this->session->get($this->sessionVariableName . '.' . $service);
        }

        throw new TokenNotFoundException('Token not found in session, are you sure you stored it?');
    }

    /**
     * @param string $service
     * @param TokenInterface $token
     *
     * @return TokenStorageInterface
     */
    public function storeAccessToken($service, TokenInterface $token)
    {
        $accessTokens = $this->session->get($this->sessionVariableName, []);
        $accessTokens[$service] = $token;

        $this->session->put($this->sessionVariableName, $accessTokens);

        // allow chaining
        return $this;
    }

    /**
     * @param string $service
     *
     * @return bool
     */
    public function hasAccessToken($service)
    {
        return $this->session->has($this->sessionVariableName . '.' . $service);
    }

    /**
     * @param string $service
     *
     * @return TokenStorageInterface
     */
    public function clearToken($service)
    {
        $this->session->forget($this->sessionVariableName . '.' . $service);

        // allow chaining
        return $this;
    }

    /**
     * @return TokenStorageInterface
     */
    public function clearAllTokens()
    {
        $this->session->forget($this->sessionVariableName);

        // allow chaining
        return $this;
    }

    /**
     * @param string $service
     * @param string $state
     *
     * @return TokenStorageInterface
     */
    public function storeAuthorizationState($service, $state)
    {
        $states = $this->session->get($this->stateVariableName, []);
        $states[$service] = $state;

        $this->session->put($this->stateVariableName, $states);

        // allow chaining
        return $this;
    }

    /**
     * @param string $service
     *
     * @return bool
     */
    public function hasAuthorizationState($service)
    {
        return $this->session->has($this->stateVariableName . '.' . $service);
    }

    /**
     * @param string $service
     *
     * @throws \OAuth\Common\Storage\Exception\AuthorizationStateNotFoundException
     *
     * @return string
     */
    public function retrieveAuthorizationState($service)
    {
        if ($this->hasAuthorizationState($service)) {
            return $this->session->get($this->stateVariableName . '.' . $service);
        }

        throw new AuthorizationStateNotFoundException('State not found in session, are you sure you stored it?');
    }

    /**
     * @param string $service
     *
     * @return TokenStorageInterface
     */
    public function clearAuthorizationState($service)
    {
        $this->session->forget($this->stateVariableName . '.' . $service);

        // allow chaining
        return $this;
    }

    /**
     * @return TokenStorageInterface
     */
    public function clearAllAuthorizationStates()
    {
        $this->session->forget($this->stateVariableName);

        // allow chaining
        return $this;
    }
}
