<?php
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();

if(!empty($_POST['videos_to_delete'])){
    $vids = $_POST['videos_to_delete'];
    var_dump($vids);
    die;
}

if(!empty($_GET['resource_id'])) {
    $video_id = $_GET['resource_id'];
    $filter = ' WHERE resource_id = "'. $_GET['resource_id'] .'"';
    $result = mysqli_query($db->con,"SELECT * FROM `videos` $filter");
    $row = mysqli_fetch_array($result);
    if($row) {
        $disk = new Disk();
        $status = $disk->removeFile($row['path']);

        $db->delete('videos', 'resource_id', $_GET['resource_id']);
        echo "Ресурс удален. Переход обратно через 2 секунды";
        echo "<meta http-equiv=\"refresh\" content=\"2;url=".$_SERVER['HTTP_REFERER']."\"/>";
        exit;
    } else {
        echo "Ресурс не найден";
        echo "<meta http-equiv=\"refresh\" content=\"2;url=".$_SERVER['HTTP_REFERER']."\"/>";
        exit;
    }
} else {
    echo "Неверный запрос";
    echo "<meta http-equiv=\"refresh\" content=\"2;url=".$_SERVER['HTTP_REFERER']."\"/>";
    exit;
}
?>
