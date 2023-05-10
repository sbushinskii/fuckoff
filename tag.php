<?php
require_once 'database.php';
require_once 'functions.php';

$tag_id = isset($_GET['id']) ? $_GET['id'] : false;
if(!(int)$tag_id) {
    echo "Fuck you";
    exit;
} else {
    $db = new Database();
    $tag = $db->getTag($tag_id);
    $vids = $db->findVideosByTag($tag_id);
}

?>

<html lang="en">
<head>
    <title>Видео по меткам</title>
    <?php include 'header.php';?>
</head>
<body>
<h1>Тэг "<?php echo $tag['title'];?>"</h1>
<a href="tag-edit.php?id=<?php echo $tag['id'];?>">Редактировать</a><br>
<?php require_once 'nav.php';?>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style='width:50px;'>Название</th>
        <th style='width:450px;'>Тэги</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $tags = $db->getTags();

    foreach ($vids as $row) {
        $assignedTags = $db->getVideoTagsIds($row['resource_id']);
        ?>
            <tr>

                <td>
                    <a target='_blank' href='<?php echo $row['public_url'];?>'><?php echo $row['name'];?></a>
                    <br>
                    <a href="video-edit.php?resource_id=<?php echo $row['resource_id'];?>">(редактировать)</a>
                </td>

                <td>
                    <?php foreach ($tags as $tag) {
                        if (in_array($tag['id'], $assignedTags)) { ?>
                            <a href="tag.php?id=<?php echo $tag['id'];?>"><?php echo $tag['title'];?> (<?php echo $tag['counter'];?>)</a><br>
                    <?php
                        }
                    }?>
                </td>
            </tr>
        <?php
    }
    ?>
    </tbody>
</table>
</body>
</html>
