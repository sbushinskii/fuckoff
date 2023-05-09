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
<?php require_once 'nav.php';?>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style='width:50px;'>ID</th>
        <th style='width:150px;'>Дата</th>
        <th style='width:50px;'>Название</th>
        <th style='width:50px;'>Путь</th>
        <th style='width:450px;'>Тэги</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $tags = $db->getTags();

    foreach ($vids as $row) {
        $assignedTags = $db->getVideoTagsIds($row['resource_id']);
        ?>
        <form class='needs-validation' method='POST'>
            <tr>
                <td><?php echo $row['id'];?></td>
                <td><?php echo $row['date'];?></td>
                <td><?php echo $row['name'];?></td>
                <td>
                    <a target='_blank' href='<?php echo $row['public_url'];?>'>Открыть</a><br><br>
                    <textarea name="path"><?php echo $row['path'];?></textarea>
                    <input type="hidden" name="old_path" value="<?php echo $row['path'];?>">
                </td>
                <td>

                    <input type="hidden" name="resource_id" value="<?php echo $row['resource_id'];?>">
                    <div class='col-sm-9'>
                        <div>
                            <select class='form-select' id='validationTagsNewSame' name='tags_new[]' multiple data-allow-new='true' data-allow-same='true'>
                                <option disabled hidden value=''>Выбор тэга...</option>
                                <?php foreach ($tags as $tag) {
                                    $is_selected = in_array($tag['id'], $assignedTags);
                                    ?>
                                    <option value="<?php echo $tag["id"];?>" <?php echo ($is_selected) ? " selected ":"";?>><?php echo $tag["title"];?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button class='btn btn-primary' type='submit'>Сохранить</button>

                </td>
            </tr>
        </form>
        <?php
    }
    ?>
    </tbody>
</table>
</body>
</html>
