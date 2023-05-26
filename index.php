<?php
    require_once 'database.php';
    require_once 'functions.php';
    $db = new Database();
    checkPostStatus();


if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

$total_records_per_page = 20;
$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";

$filter = '';
$type = '';

$order = isset($_GET['order']) ? $_GET['order'] : "DESC";

if (isset($_GET['no_tags'])) {
    $result_count = mysqli_query($db->con, "SELECT COUNT(*) as total_records FROM videos where resource_id  NOT IN (select DISTINCT(video_id) FROM video_tag)");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];

    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1

    $sql_exec = "SELECT * FROM videos where resource_id  NOT IN (select DISTINCT(video_id) FROM video_tag) order by `type` $order LIMIT $offset, $total_records_per_page";
    $result = mysqli_query($db->con, $sql_exec);
} else {
    if (isset($_GET['type']) && trim($_GET['type'])) {
        $type = $_GET['type'];
        $filter = " WHERE type='$type'";
    }

    $result_count = mysqli_query($db->con, "SELECT COUNT(*) As total_records FROM `videos`" . $filter);
    $total_records = mysqli_fetch_array($result_count);

    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1

    $result = mysqli_query($db->con, "SELECT * FROM `videos` $filter order by `timestamp` $order LIMIT $offset, $total_records_per_page");
}

while ($row = mysqli_fetch_array($result)) {
    $assignedTags = $db->getVideoTagsIds($row['resource_id']);
    $vids[] = [
        'video' => $row,
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
                <h1>Все видео</h1>
                <div>
                    <h4>
                        <a href="?type=common">Видео</a>
                    </h4>
                    <h4>
                        <a href="?type=moments">Приятные моменты</a>
                    </h4>
                    <h4>
                        <a href="?no_tags=1">Без меток</a>
                    </h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php require_once 'include/table.php';?>
            </div>
            <div class="col-md-12">
                <?php require_once 'include/pagination.php';?>
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

