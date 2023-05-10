<?php
require_once '../database.php';
require_once '../functions.php';
$disk = new Disk();

$db = new Database();

$videosNoPreview = $db->getVideosNoPreview();

foreach ($videosNoPreview as $item) {
    $save_path = 'preview/' . $item['resource_id'] . '.mp4';
    $disk->grabPreviewFile($item['path'], $save_path);

    echo "Grabbed " . $item['name'] . PHP_EOL;
    $preview_filename = str_replace('.mp4', '.png', $save_path);
    shell_exec("/usr/local/bin/ffmpeg -i '$save_path' '$preview_filename'");
    if(file_exists($preview_filename) && filesize($preview_filename)){
        $db->update('videos', 'resource_id', $item['resource_id'], 'preview', $preview_filename);
        shell_exec("rm $save_path");
    }
}
