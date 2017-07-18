<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MSocialAuth\Process;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Symfony\Component\HttpFoundation\Request;

class FacebookProcess implements ProcessInterface {

    /**
     *
     * @var Facebook
     */
    private $fb;
    private $config;
    private $userProfile;

    public function getUserProfile() {
        return $this->userProfile;
    }

    public function setUserProfile($userProfile) {
        $this->userProfile = $userProfile;
        return $this;
    }

    public function __construct(array $config) {
        $this->config = $config;
        $this->fb = new Facebook([
            "app_id" => $config['facebook']['id'],
            "app_secret" => $config['facebook']['secret']
        ]);
    }

    /**
     * 
     * @return String url de login no facebok
     */
    public function getUrlLogin() {
        $helper = $this->fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl($this->config['redirect_url'], $this->config['facebook']['scope']);
        return $loginUrl;
    }

    /**
     * 
     * @return Array
     */
    public function getProfile() {
        $profile = json_decode($this->getUserProfile(),true);
        $user = [
            "uid" => $profile['id'],
            "first_name" => $profile['first_name'],
            "last_name" => $profile['last_name'],
            "email"  => $profile['email'],
            "gender" => $profile['gender'],
            "picture" => $profile['picture']['data']['url']            
        ];
        return $user;
    }

    /**
     * 
     * @param Request $request
     * @return MSocialAuth\Process\FacebookProcess
     */
    public function callbackProcess(Request $request) {
        $fb = $this->fb;

        $helper = $fb->getRedirectLoginHelper();
        if ($request->query->has('state')) {
            $helper->getPersistentDataHandler()->set('state', $request->get('state'));
        }
        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookSDKException $e) {
            echo $e->getMessage();
            die();
        }
        $profile = $fb->get('/me?fields=id,first_name,last_name,email,gender,locale,picture', $accessToken);        
        $this->setUserProfile($profile->getBody());
        return $this;
    }

}
