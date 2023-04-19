<?php
require_once 'functions.php';
$disk = new Disk();

$templates = [
    'moments'=>'disk:/Видео_моменты/%s',
    'common'=>'disk:/Видео/%s',
];

//$years = range(1996, date('Y'));
if(isset($argv[1])){
    $years = [$argv[1]];
} else {
    $years = [1996, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023];
}

$rescan_counter = 0;
$my_files = [];
$errors = [];
$Misha_bd = '2013-01-15';
$Vera_bd  = '2016-04-19';

$total_files = 0;
foreach ($templates as $key=> $template) {
    echo "Scanning $key: " . $template . PHP_EOL;
    $my_files = [];
    foreach ($years as $year) {

        $param = sprintf($template, $year);
        $files = $disk->getFiles(urlencode($param), 1000);

        echo "Processing " . $year . PHP_EOL;
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
                    var_dump("sharing: " . $filename);
                    $rescan_counter++;
                } else {
                    $total_files++;
                    $my_files[] = [
                        'unique_date' => $unique_day,
                        'date' => date('d.m.Y', strtotime($real_date)),
                        'date_formatted' => date('d.m.Y', strtotime($real_date)),
                        'path' => $resource->path,
                        'name' => $filename,
                        'size' => $disk->formatBytes($resource->size),
                        'public_url' => $resource->public_url,
                        'Misha' => dateDiff($real_date, $Misha_bd),
                        'Vera' => dateDiff($real_date, $Vera_bd),
                        'type'=>$key
                    ];
                }
            }
        }
    }
    $path = "disk:/playlist/playlist_$key.json";
    $upload_URL = $disk->getPlaylistUploadURL($path);
    $upload_status = $disk->uploadPlaylist($upload_URL, json_encode($my_files, JSON_UNESCAPED_UNICODE));
}
//Save errors
$path = "disk:/playlist/errors.json";
$upload_URL = $disk->getPlaylistUploadURL($path);
$disk->uploadPlaylist($upload_URL, json_encode($errors, JSON_UNESCAPED_UNICODE));

//Upload playlist


echo "Erros: " . count($errors).PHP_EOL;
echo "Files: " . $total_files.PHP_EOL;
echo "Files to rescan: " . $rescan_counter.PHP_EOL;



