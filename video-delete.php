<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();

if(!empty($_GET['resource_id'])) {
    $video_id = $_GET['resource_id'];
    $filter = ' WHERE resource_id = "'. $_GET['resource_id'] .'"';
    $result = mysqli_query($db->con,"SELECT * FROM `videos` $filter");
    $row = mysqli_fetch_array($result);
    if($row) {
//        $db->update('videos', $video_id, 'is_active', 0);
//        $db->update('videos', $video_id, 'is_deleted', 1);

        $disk = new Disk();
        $status = $disk->removeFile($row['path']);

        $db->delete('videos', $_GET['resource_id']);
        echo "OK";
    } else {
        echo "Ресурс не найден";
    }
} else {
    echo "Неверный запрос";
}
?>
