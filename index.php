<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');


require __DIR__ . '/vendor/autoload.php';


use Rafa\Http\Rest;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (isset($_REQUEST) && !empty($_REQUEST)) {

   // print_r($_REQUEST) ;
    $rest = new Rest($_REQUEST);
    echo $rest->run();
}