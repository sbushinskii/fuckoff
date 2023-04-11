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

require_once 'functions.php';
$disk = new Disk();
$disk->downloadPlaylist();

$message = '';
$date = getUniqueDate(date('Y-m-d'));
$this_day_vids_data = findVids($date);
foreach ($this_day_vids_data as $type=>$this_day_vids) {
    if(!empty($this_day_vids)) {
        switch ($type){
            case 'common': {
                $prefix = '';
            }break;
            case 'moments': {
                $prefix = '(прекрасные моменты)';
            }break;
            default: {
                $prefix = '';
            }
        }
        formatMessage($this_day_vids, "Cегодня в этот день $prefix:", $message);
//        foreach ($subscribers as $subscriber) {
//            $result = $greenApi->sending->sendMessage($subscriber, $message);
//        }
        sendMessageTelegram($message);
    } else {
        echo "NO vids today :(";
    }
}

