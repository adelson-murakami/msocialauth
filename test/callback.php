<?php 

ini_set('display_errors', 1);
require_once dirname(__DIR__)."/vendor/autoload.php";

if (!session_id()) {
    session_start();
}

$config = [
    'redirect_url' => "http://localhost:8080/callback.php",
    'facebook' => [
        "id"=> 1537183879928651,
        "secret"=> "4eecb47701426a18a674d2420355dd8b",
        "scope" => ["email"]
    ],
    'google' => [
        "id" => "540135089842-jevhcd4g7bshtl00rs13uj9sej56dkcn.apps.googleusercontent.com",
        "secret" => "B2A68RgRvKaPTTMgtY0YjTM0",
        "scope" => ["email", "profile"]
    ]
];

$msocialauth = new \MSocialAuth\MSocialAuth($config);
$msocialauth->setProvider('facebook');
var_dump($msocialauth->callbackProvider());