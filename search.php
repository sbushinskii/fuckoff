<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();

$vids = false;
if(isset($_POST['search'])){
    $search = $_POST['search'];

    $tags = $db->getTags();
    $vids = [];
    foreach ($db->searchVideosByTitle($search) as $row) {
        $assignedTags = $db->getVideoTagsIds($row['resource_id']);
        $vids[] = [
            'video'=>$row,
            'tags' => $assignedTags
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
            <input class="form-control" type="text" name="search" placeholder="Search" aria-label="Search" value="<?php echo isset($_POST['search'])?$_POST['search']:"";?>">
        </form>
    </div>
</div>
    <?php require_once 'include/video-table.php';?>
</body>
</html>