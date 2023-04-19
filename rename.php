<?php
//require './vendor/autoload.php';
//use GreenApi\RestApi\GreenApiClient;
//
//define( "ID_INSTANCE", "1101807129" );
//define( "API_TOKEN_INSTANCE", "5d13a45aa24f44a288d09b62c66396c721c1878467d84952a4" );
//
//$greenApi = new GreenApiClient( ID_INSTANCE, API_TOKEN_INSTANCE );
//
//$subscribers = ['79236792777@c.us'];
//if(isset($argv[1])){
//    switch ($argv[1]) {
//        case 'all':
//            {
//                $subscribers = ['79236792777@c.us', '79785904777@c.us', '79237636424@c.us'];
//            }
//            break;
//        default: {
//
//        }
//    }
//}
$debug = isset($argv[1])&&($argv[1]=='debug');

require_once 'functions.php';
$disk = new Disk();

$from = $argv[1];
$to = $argv[2];


$parts = explode(' ', $from);
$from_path = $parts[0];
$from_ext = explode('.', $from)[1];
$to_path = $from_path . ' ' . $to . '.' . $from_ext;
var_dump(urlencode($from), urlencode($to_path));die;
$response = $disk->rename(urlencode($from), urlencode($to_path));
var_dump($response);die;