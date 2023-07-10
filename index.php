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
<!doctype html>
<html lang="en">
<head>
    <title>Видосы</title>
    <?php require_once 'header.php';?>
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="justify-content-center">
            <div >
                <?php require_once 'nav.php';?>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php require_once 'include/table.php';?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button onclick="document.location='index.php?send=true'" class='btn btn-primary' type='submit'>Отправить в телеграм</button>
                </div>
            </div>
        </div>
</section>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
