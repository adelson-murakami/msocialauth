<?php

namespace MSocialAuth;

class MSocialAuth extends \MSocialAuth\Provider\MSocialProvider {

    public function __construct(array $config, $isAuhtenticate = true) {
        parent::__construct($config);        
    }   
}
