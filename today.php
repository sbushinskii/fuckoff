<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();

if(isset($_GET['send'])){
    sendTodayVideos();
}

if(!empty($_POST)) {
    $video_id = $_POST['resource_id'];
    $db->clearTags($video_id);

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

        $db->update('videos', $video_id, 'name', $filename);
        $db->update('videos', $video_id, 'path', $new_path);
    }
}


?>
<html>
<head>
    <?php require_once 'header.php';?>
</head>
<body>
<div >

<h3>Видео Сегодня</h3>
<?php require_once 'nav.php';?>


<table class="table table-striped table-bordered">
<thead>
<tr>
    <th style='width:150px;'>Дата</th>
    <th style='width:50px;'>Название</th>
    <th style='width:50px;'>Путь в облаке</th>
    <th style='width:450px;'>Тэги</th>
</tr>
</thead>
<tbody>
<?php
    $filter = ' WHERE unique_date = "'.getUniqueDate(date('Y-m-d')).'"';

	$result_count = mysqli_query($db->con,"SELECT COUNT(*) As total_records FROM `videos`".$filter);
	$total_records = mysqli_fetch_array($result_count);

	$total_records = $total_records['total_records'];

    $result = mysqli_query($db->con,"SELECT * FROM `videos` $filter");
    $tags = $db->getTags();


    while($row = mysqli_fetch_array($result)){
        $assignedTags = $db->getVideoTagsIds($row['resource_id']);
        ?>
        <form class='needs-validation' method='POST'>
            <tr>
                    <td><?php echo $row['date'];?></td>
                    <td>
                        <a target='_blank' href='<?php echo $row['public_url'];?>'><?php echo $row['name'];?></a>
                        <br>
                        <a target='_blank' href="video-edit.php?resource_id=<?php echo $row['resource_id'];?>">(редактировать)</a>
                    </td>
                    <td>
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
	mysqli_close($db->con);
    ?>
</tbody>
</table>

    <button onclick="document.location='today.php?send=true'" class='btn btn-primary' type='submit'>Отправить в телеграм</button>

</div>
</body>
</html>