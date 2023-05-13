<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();

if(!empty($_POST)) {
    $video_id = $_POST['resource_id'];
    $db->clearTags($video_id);

    //upload
    if(isset($_FILES["preview"])) {
        $target_dir = __DIR__ . "/images/";
        $target_file = $target_dir . $video_id . ".png";

        $check = getimagesize($_FILES["preview"]["tmp_name"]);
        if($check){
            if (move_uploaded_file($_FILES["preview"]["tmp_name"], $target_file)) {
                $db->update('videos', 'resource_id', $video_id, 'skip_preview', '0');
                $db->update('videos', 'resource_id', $video_id, 'preview', $video_id . ".png");
            }
        }
    }

    //save tags
    $tag_ids = [];
    if(isset($_POST['tags_new'])) {
        foreach ($_POST['tags_new'] as $tag_name) {
            if (!(int)$tag_name + 0) {
                $record = [
                    'title' => $tag_name
                ];
                $tag_id = $db->insert('tags', $record);
            } else {
                $tag_id = $tag_name;
            }
            $tags = [
                'tag_id' => $tag_id,
                'video_id' => $video_id,
            ];
            $status = $db->insert('video_tag', $tags);
        }
    }
    if($_POST['path'] !== $_POST['old_path']) {
        $new_path = $_POST['path'];
        $disk = new Disk();
        $disk->rename(urlencode($_POST['old_path']), urlencode($new_path));

        $date_meta = explode(' ', $new_path)[0];
        $date = strtotime($date_meta);
        $filename_no_extension = pathinfo($new_path, PATHINFO_FILENAME);
        $filename = trim(str_replace($date_meta, '', $filename_no_extension));

        $db->update('videos', 'resource_id', $video_id, 'path', $new_path);
    }
    $db->update('videos','resource_id', $video_id, 'name', $_POST['name']);
    $db->update('videos','resource_id', $video_id, 'type', $_POST['type']);
}


$filter = ' WHERE resource_id = "' . $_GET['resource_id'] . '"';

$result = mysqli_query($db->con, "SELECT * FROM `videos` $filter");
$tags = $db->getTags();
$row = mysqli_fetch_array($result);


?>
<html>
<head>
    <?php require_once 'header.php';?>
</head>
<body>
<div >

    <h1>Редактирование Видео</h1>
    <?php require_once 'nav.php';?>

    <?php
    $video_active = true;
    if(!$row) {
        $video_active = false; ?>
        <h1>Видео удалено</h1>
        <?php
        exit;
    }
    $assignedTags = $db->getVideoTagsIds($row['resource_id']);
    ?>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th style='width:150px;'>Дата</th>
            <th style='width:150px;'>Изображение</th>
            <th style='width:50px;'>Название</th>
            <th style='width:50px;'>Путь в облаке</th>
            <th style='width:50px;'>Тип</th>
            <th style='width:450px;'>Тэги</th>
        </tr>
        </thead>
        <tbody>

            <form class='needs-validation' method='POST' enctype="multipart/form-data">
                <tr>
                    <td>
                        <?php echo $row['date'];?><br><br>
                        <a target='_blank' href='<?php echo $row['public_url'];?>'>
                            Ссылка<br><br>
                        </a>
                    </td>
                    <td>
                        <?php if(!$row['skip_preview']){ ?>
                            <img src="images/<?php echo $row['preview'];?>"><br>
                        <?php } ?>
                        <input type="file" name="preview">
                    </td>
                    <td>
                        <input type="text" name="name" value="<?php echo $row['name'];?>">
                    </td>
                    <td>
                        <textarea name="path"><?php echo $row['path'];?></textarea>
                        <input type="hidden" name="old_path" value="<?php echo $row['path'];?>">
                    </td>
                    <td>
                        <select name="type" class="ignore-tags">
                            <option value="common" <?php echo ($row['type']=='common') ? " selected ":""; ?>>Общее</option>
                            <option value="moments" <?php echo ($row['type']=='moments') ? " selected ":""; ?>>Моменты</option>
                        </select>
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
                        <button class='btn btn-primary btn-danger' onclick="return removeVideo()" type='button'>Удалить</button>

                    </td>
                </tr>
            </form>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function removeVideo(){
        if(confirm("Уверен?")){
            document.location='video-delete.php?resource_id=<?php echo $row['resource_id'];?>'
        }
        return false;
    }
</script>
</body>
</html>