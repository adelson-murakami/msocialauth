<?php

namespace MSocialAuth\Provider;

use MSocialAuth\Process\FacebookProcess;
use MSocialAuth\Process\GoogleProcess;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MSocialProvider {

    private $provider = 'facebook';
    private $config;

    public function getProvider() {
        return $this->provider;
    }

    public function setProvider($provider) {
        $this->provider = $provider;
        return $this;
    }

    public function __construct(array $config) {
        $this->config = $config;
    }
    
    /**     
     * @return FacebookProcess
     */
    public function getInstanceProvider() {
        if (strtolower($this->getProvider()) === 'facebook') {
            return new FacebookProcess($this->config);
        } else if (strtolower($this->getProvider()) === 'google') {
            return new GoogleProcess($this->config);
        }
    }

    /**
     * redirect for page provider Login
     */
    public function authenticate() {
        $provider = $this->getInstanceProvider();
        $this->redirect($this->getInstanceProvider()->getUrlLogin());
    }

    /**
     * @return type
     */
    public function callbackProvider() {
        return $this->getInstanceProvider()
                ->callbackProcess(Request::createFromGlobals())
                ->getProfile();
    }

    /**
     * @param string $path
     */
    protected function redirect(string $path) {
        $redirect = new RedirectResponse($path);
        $redirect->prepare(Request::createFromGlobals());
        $redirect->send();
    }

}
