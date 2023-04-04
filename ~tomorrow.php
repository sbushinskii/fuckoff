<?php
require_once 'functions.php';
$disk = new Disk();
$disk->downloadPlaylist();

$message = '';
$date = getUniqueDate(date('Y-m-d'));

    $shift = 3;
    $upcoming_vids = findVids($this_day_vids, $shift, true);
    formatMessage($upcoming_vids, 'Ближайшие события:', $message);


    $previous_vids = findVids($this_day_vids, $shift, false);
    formatMessage($previous_vids, 'Прошедшие события:', $message);

sendMessage($message);
