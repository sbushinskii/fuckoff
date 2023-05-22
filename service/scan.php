<?php
require_once '../database.php';
require_once '../functions.php';
$disk = new Disk();

$templates = [
    'moments'=>'disk:/Видео_моменты/%s',
    'common'=>'disk:/Видео/%s',
];

//$years = range(1996, date('Y'));
if(isset($_GET['mode'])&&$_GET['mode'] == 'light') {
    $years = [2023];
} else {
    if (isset($argv[1])) {
        $years = [$argv[1]];
    } else {
        $years = [1996, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023];
    }
}

$DB = new Database();

$rescan_counter = 0;
$my_files = [];
$errors = [];
$Misha_bd = '2013-01-15';
$Vera_bd  = '2016-04-19';
$db = [];
$total_files = 0;
$debug = false;
foreach ($templates as $key=> $template) {
    if($debug) {
        echo "Scanning $key: " . $template . PHP_EOL;
    }
    $my_files = [];
    foreach ($years as $year) {

        $param = sprintf($template, $year);

        $files = $disk->getFiles(urlencode($param), 1000);
        if($debug) {
            echo "Processing " . $year . PHP_EOL;
            echo "<br>";
        }
        foreach ($files as $resource) {
            $date_meta = explode(' ', $resource->name)[0];
            $date = strtotime($date_meta);
            $filename_no_extension = pathinfo($resource->name, PATHINFO_FILENAME);
            $filename = trim(str_replace($date_meta, '', $filename_no_extension));

            $real_date = date('Y-m-d', $date);
            if ($real_date == '1970-01-01') {
                $errors[] = [
                    'date' => $real_date,
                    'path' => $resource->path,
                ];
            } else {
                $date_parts = explode('-', $real_date);
                $unique_day = str_replace($date_parts[0], '1970', $real_date);

                if (!isset($resource->public_url)) {
                    $disk->setPubicUrl(urlencode($resource->path));
                    if($debug) {
                        echo("sharing: " . $filename);
                        echo "<br>";
                    }
                    $rescan_counter++;
                } else {
                    $total_files++;
                    $my_files[] = [
                        'unique_date' => $unique_day,
                        'date' => date('d.m.Y', strtotime($real_date)),
                        'timestamp' => strtotime($real_date),
                        'date_formatted' => date('d.m.Y', strtotime($real_date)),
                        'path' => $resource->path,
                        'name' => $filename,
                        'size' => $disk->formatBytes($resource->size),
                        'public_url' => $resource->public_url,
                        'Misha' => dateDiff($real_date, $Misha_bd),
                        'Vera' => dateDiff($real_date, $Vera_bd),
                        'type'=>$key
                    ];

                    $db = [
                        'resource_id' => $resource->md5,
                        'unique_date' => $unique_day,
                        'date' => date('d.m.Y', strtotime($real_date)),
                        'timestamp' => strtotime($real_date),
                        'date_formatted' => date('d.m.Y', strtotime($real_date)),
                        'path' => $resource->path,
                        'name' => $filename,
                        'public_url' => $resource->public_url,
                        'Misha' => dateDiff($real_date, $Misha_bd),
                        'Vera' => dateDiff($real_date, $Vera_bd),
                        'type'=>$key
                    ];

                    if(!$DB->getVideo($resource->md5)) {
                        $DB->insert('videos', $db);
                    } else {

                    }
                }
            }
        }
    }
    $path = "disk:/playlist/playlist_$key.json";
    $upload_URL = $disk->getPlaylistUploadURL($path);
    $upload_status = $disk->uploadPlaylist($upload_URL, json_encode($my_files, JSON_UNESCAPED_UNICODE));
}

$path = "disk:/playlist/db.json";
$upload_URL = $disk->getPlaylistUploadURL($path);
$upload_status = $disk->uploadPlaylist($upload_URL, json_encode($db, JSON_UNESCAPED_UNICODE));

//Save errors
$path = "disk:/playlist/errors.json";
$upload_URL = $disk->getPlaylistUploadURL($path);
$disk->uploadPlaylist($upload_URL, json_encode($errors, JSON_UNESCAPED_UNICODE));

echo "<br>";
echo "БД обновлена";
echo "<br>";
echo "<a href='/service/index.php'>ОК</a>";
echo "<br>";
if($debug) {
    echo "Erros: " . count($errors) . PHP_EOL;
    echo "Files: " . $total_files . PHP_EOL;
    echo "Files to rescan: " . $rescan_counter . PHP_EOL;
}



