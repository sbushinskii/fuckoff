<?php
require_once '../database.php';
require_once '../functions.php';
$disk = new Disk();

$db = new Database();

$videosNoPreview = $db->getVideosNoPreview();

$total = count($videosNoPreview);
$current = 0;
foreach ($videosNoPreview as $item) {
    $preview_original_name = $item['resource_id'] . '.mp4';
    $preview_filename = str_replace('.mp4', '.png', $preview_original_name);

    $save_path = '/Users/sbushinskii/workspace/today/images/';
    $disk->grabPreviewFile($item['path'], $save_path . $preview_original_name);

    $current++;
    echo $current . "/" .$total. " " . $item['name'] . PHP_EOL;

    $source = $save_path.$preview_original_name;
    $output = $save_path.$preview_filename;

    shell_exec("/usr/local/bin/ffmpeg -y -i '$source' '$output' 2>&1 ");
    if(file_exists($output) && filesize($output)){
        $db->update('videos', 'resource_id', $item['resource_id'], 'preview', $preview_filename);
        shell_exec("rm $source");
    }

}
