<?php
require_once '../database.php';
require_once '../functions.php';
$disk = new Disk();

$db = new Database();

$vids = $db->getVideos();

foreach ($vids as $item) {
    $timestamp = strtotime($item['date']);
    $db->update('videos', 'resource_id', $item['resource_id'], 'timestamp', $timestamp);
}
