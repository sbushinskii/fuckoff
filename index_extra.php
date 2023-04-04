<?php
require_once 'functions.php';
$disk = new Disk();

$template = 'disk:/Фотокамера/';
$copy_dir = 'disk:/video_copy/';

$rescan_counter = 0;
$my_files = [];

$offset = 0;
$scan = true;
while($scan) {
    var_dump($offset);
    $files = $disk->getFiles(urlencode($template), 100, $offset);
    if(empty($files)) {
        $scan = false;
    } else {
        foreach ($files as $resource) {
            if($resource->media_type!='image') {
                var_dump($resource->media_type);
            }
//            if ($resource->media_type == 'video') {
//                $new_file = $copy_dir . $resource->exif->date_time.$resource->md5.urlencode($resource->name);
//                $status = $disk->copyFile(urlencode($resource->path), $new_file);
//                if($status->href){
//                    var_dump($status->href);
//                    $disk->removeFile($resource->path);
//                }
//            }
        }
        $offset++;

    }
}

echo "Files: " . count($my_files).PHP_EOL;
echo "Files to rescan: " . $rescan_counter.PHP_EOL;



