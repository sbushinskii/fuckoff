<?php
$debug = isset($argv[1])&&($argv[1]=='debug');

require_once 'functions.php';
$disk = new Disk();


$message = '';
$date = getUniqueDate(date('Y-m-d'));

//$types = ['common','moments'];
$types = ['common'];
foreach($types as $type) {
    $disk->downloadPlaylist($type);
    $this_day_vids_data = findVids($date, $type);

    foreach ($this_day_vids_data as $type => $this_day_vids) {
        if (!empty($this_day_vids)) {
            $prefix = ($type == 'moments') ? '(прекрасные моменты)' : '';

            formatMessage($this_day_vids, "Cегодня в этот день $prefix:", $message, $debug);
            sendMessageTelegram($message);
        } else {
            echo "NO vids today :(";
        }
    }
}


