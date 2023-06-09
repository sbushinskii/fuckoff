<?php
require_once 'database.php';
require_once 'functions.php';
$disk = new Disk();

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
    $preview_original_name = $item['resource_id'] . '.mp4';
    $preview_filename = str_replace('.mp4', '.png', $preview_original_name);

    $disk->grabPreviewFile($item['path'], $save_path . $preview_original_name);
    echo $current . "/" . $total . " " . $item['name'];
    if(filesize($save_path . $preview_original_name)) {
        $current++;
        $source = $save_path . $preview_original_name;
        $output = $save_path . $preview_filename;

        shell_exec("/usr/local/bin/ffmpeg -y -i '$source' '$output' 2>&1 ");
        if (file_exists($output) && filesize($output)) {
            $db->update('videos', 'resource_id', $item['resource_id'], 'preview', $preview_filename);
            shell_exec("rm $source");
            echo "- ok".PHP_EOL;
        } else {
            shell_exec("/usr/local/bin/convert  '$source' '$output' 2>&1 ");
            if (file_exists($output) && filesize($output)) {
                $db->update('videos', 'resource_id', $item['resource_id'], 'preview', $preview_filename);
                shell_exec("rm $source");
            } else {
                echo "- thumbnail processing error".PHP_EOL;
            }

        }
    } else {
        $db->update('videos', 'resource_id', $item['resource_id'], 'skip_preview', '1');
        echo " - Skip preview ...". PHP_EOL;
    }
}

echo "Процесс завершен. Переход обратно через 2 секунды";
echo "<meta http-equiv=\"refresh\" content=\"2;url=".$_SERVER['HTTP_REFERER']."\"/>";
exit;
