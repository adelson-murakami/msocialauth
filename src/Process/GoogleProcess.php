<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MSocialAuth\Process;

use Symfony\Component\HttpFoundation\Request;

/**
 * Description of GoogleProcess
 *
 * @author adelson
 */
class GoogleProcess implements ProcessInterface {
    
    private $config;

    /**
     *
     * @var  \Google_Client
     */
    private $goo;
        
    /**
     *
     * @var \Google_Service_Oauth2
     */
    private $service;
    
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
        $this->goo = new \Google_Client();
        $this->goo->setClientId($this->config['google']['id']);
        $this->goo->setClientSecret($this->config['google']['secret']);
        $this->goo->setIncludeGrantedScopes(true);
        $this->goo->setRedirectUri($this->config['redirect_url']);
        foreach($this->config['google']['scope'] as $scope){
            $this->goo->addScope($scope);
        }
        
        $this->service = new \Google_Service_Oauth2($this->goo);
    }

    public function callbackProcess(Request $request) {
        
        if($request->query->has('code')){
            $auth = $this->goo->authenticate($request->get('code'));
            try{                
                $profile = $this->service->userinfo->get();                
                $this->setUserProfile($profile);                
            } catch (\Google_Service_Exception $ex) {
                echo $ex->getMessage();
                die();
            }           
        }
        
        return $this;
    }

    public function getProfile() {
        $profile = $this->getUserProfile();        
        $user = [
            "uid" => $profile->id,
            "first_name" => $profile->givenName,
            "last_name" => $profile->familyName,
            "email" =>  $profile->email,
            "gender" => $profile->gender,
            "picture" => $profile->picture
        ];
        return $user;
    }

    public function getUrlLogin() {
        return $this->goo->createAuthUrl();
    }

}
