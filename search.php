<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();
checkPostStatus();

session_start();
$vids = false;
if(isset($_POST['search'])) {
    $_SESSION['search'] = $_POST['search'];
}

$search = (isset($_SESSION['search']) && trim($_SESSION['search'])) ? $_SESSION['search'] : false;

$vids = [];
if($search) {
    foreach ($db->searchVideosByTitle($search) as $row) {
        $assignedTags = $db->getVideoTagsIds($row['resource_id']);
        $vids[] = [
            'video' => $row,
            'assignedTags' => $assignedTags
        ];
    }
}


?>
<html>
<head>
    <?php require_once 'header.php';?>
</head>
<body>
<div >
<?php require_once 'nav.php';?>
<h1>Поиск</h1>

<div class="table">
    <div class="md-form mt-0">
        <form method="POST">
            <input class="form-control" type="text" name="search" placeholder="Search" aria-label="Search" value="<?php echo $search;?>">
        </form>
    </div>
</div>
    <?php require_once 'include/video-table.php';?>
</body>
</html>