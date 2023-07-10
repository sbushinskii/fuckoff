<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();
checkPostStatus();

$vids = false;
if(isset($_GET['search'])) {
    $_SESSION['search'] = $_GET['search'];
}

$search = (isset($_SESSION['search']) && trim($_SESSION['search'])) ? $_SESSION['search'] : false;

$vids = [];
$show_pagaintion = false;
if($search) {
    $show_pagaintion = true;
    if (isset($_GET['page_no']) && $_GET['page_no']!="") {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }

    $total_records_per_page = 20;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $result_count = mysqli_query($db->con, "SELECT COUNT(*) as total_records FROM videos where `name` like '%$search%'");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];

    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1; // total page minus 1

    foreach ($db->searchVideosByTitle($search,  $offset, $total_records_per_page) as $row) {
        $assignedTags = $db->getVideoTagsIds($row['resource_id']);
        $vids[] = [
            'video' => $row,
            'assignedTags' => $assignedTags
        ];
    }
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
        <?php if(!empty($vids)){ ?>

            <div class="alert alert-primary d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0" style="margin-right: .5rem!important" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#info-fill"/></svg>
                <div>
                    Найдено <?php echo $total_records;?> записей
                </div>
            </div>
        <?php } ?>

        <div class="justify-content-center">
            <div >
                <?php require_once 'nav.php';?>
            </div>

                <div class="col-md-12">
                    <div class="table">
                        <div class="md-form mt-0">
                            <form method="GET">
                                <input class="form-control" type="text" name="search" placeholder="Search" aria-label="Search" value="<?php echo $search;?>">
                            </form>
                        </div>
                    </div>
                    <?php require_once 'include/table-notags.php';?>
                </div>
            <?php if($show_pagaintion) { ?>
                <div class="col-md-12">
                    <?php require_once 'include/pagination.php';?>
                </div>
            <?php } ?>
        </div>
</section>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>

