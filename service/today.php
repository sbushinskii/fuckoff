<?php
$debug = isset($argv[1])&&($argv[1]=='debug');

require_once '../functions.php';
sendTodayVideos();

