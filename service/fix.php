<?php
require_once '../database.php';
$db = new Database();

$videosNoPreview = $db->getVideosNoPreview();
if(empty($videosNoPreview)){
    echo "Все ок, все видео в порядке";
    echo "<br>";
    echo "<a href='/service/index.php'>ОК</a>";
}

$total = count($videosNoPreview);
$current = 1;
$save_path = str_replace('service', '', __DIR__) . 'images/';

foreach ($videosNoPreview as $item) {
    $preview = $save_path . $item['resource_id'] . '.png';
    if(file_exists($preview)){
        $db->update('videos', 'resource_id', $item['resource_id'], 'preview', $item['resource_id'] . '.png');
        $db->update('videos', 'resource_id', $item['resource_id'], 'skip_preview', 1);
    }
}