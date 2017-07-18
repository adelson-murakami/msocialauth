<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MSocialAuth\Process;

use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author adelson
 */
interface ProcessInterface {
           
    public function getUrlLogin();
    
    public function getProfile();
    
    public function callbackProcess(Request $request);
        
}
