<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();
checkPostStatus();

if(isset($_GET['send'])){
    sendTodayVideos($all = true);
}
$order = isset($_GET['order']) ? $_GET['order'] : "DESC";

$filter = ' WHERE unique_date = "'.getUniqueDate(date('Y-m-d')).'"';

$result_count = mysqli_query($db->con,"SELECT COUNT(*) As total_records FROM `videos`".$filter);
$total_records = mysqli_fetch_array($result_count);

$total_records = $total_records['total_records'];

$result = mysqli_query($db->con,"SELECT * FROM `videos` $filter ORDER BY `timestamp` $order");

$vids = [];
while($row = mysqli_fetch_array($result)) {
    $assignedTags = $db->getVideoTagsIds($row['resource_id']);
    $vids[] = [
            'video'=>$row,
            'assignedTags' => $assignedTags,
    ];
}
?>
<html>
<head>
    <?php require_once 'header.php';?>
</head>
<body>
<div >
<?php require_once 'nav.php';?>
<h1>Видео Сегодня</h1>

<?php require_once 'include/video-table.php';?>

<button onclick="document.location='today.php?send=true'" class='btn btn-primary' type='submit'>Отправить в телеграм</button>

</div>
</body>
</html>