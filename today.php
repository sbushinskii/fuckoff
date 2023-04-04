<?php
require_once 'functions.php';
$disk = new Disk();
$disk->downloadPlaylist();

$message = '';
$date = getUniqueDate(date('Y-m-d'));
$this_day_vids = findVids($date);
if(!empty($this_day_vids)) {
    formatMessage($this_day_vids, 'Сегодня в этот день:', $message);
    sendMessage($message);
} else {
    echo "NO vids today :(";
}
