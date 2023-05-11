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
    $vids = $db->searchVideosByTitle($search);

    $tags = $db->getTags();
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
            <input class="form-control" type="text" name="search" placeholder="Search" aria-label="Search">
        </form>
    </div>
</div>

    <?php if($vids) { ?>
    <table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style='width:150px;'>Дата</th>
        <th style='width:50px;'>Название</th>
        <th style='width:450px;'>Метки</th>
    </tr>
    </thead>
    <tbody>
    <?php
        foreach($vids as $row) {
            $assignedTags = $db->getVideoTagsIds($row['resource_id']);
            ?>
                <tr>
                        <td>
                            <?php echo $row['date'];?><br>
                            <img src="images/<?php echo $row['preview'];?>">
                            <a target='_blank' href="video-edit.php?resource_id=<?php echo $row['resource_id'];?>">(редактировать)</a>
                        </td>
                        <td>
                            <a target='_blank' href='<?php echo $row['public_url'];?>'><?php echo $row['name'];?></a>
                        </td>
                    <td>
                        <div class='col-sm-9'>
                            <div>
                                <?php foreach ($tags as $tag) {
                                    if(in_array($tag['id'], $assignedTags)) {
                                        ?>
                                        <a href="tag.php?id=<?php echo $tag['id']; ?>"><?php echo $tag['title']; ?></a>,
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </td>
              </tr>
        <?php
            }
        ?>
    </tbody>
    </table>
</div>
<?php } ?>
</body>
</html>