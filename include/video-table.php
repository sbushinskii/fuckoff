<?php

if(!empty($_POST) && isset($_POST['tags_new'])) {
    $db = new Database();
    foreach ($_POST['tags_new'] as $video_id => $tags) {
        $db->clearTags($video_id);
        $tag_ids = [];
        foreach ($tags as $tag_name) {
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
}

$tags = $db->getTags();
?>

<?php if(!empty($vids)){ ?>

<form class='needs-validation' method='POST'>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style='width:150px;'>Дата</th>
        <th style='width:50px;'>Название</th>
        <th style='width:50px;'>Категория</th>
        <th style='width:450px;'>Метки</th>
        <th style='width:450px;'>Тэги</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($vids as $row) {
        $assignedTags = $row['assignedTags'];
        $row = $row['video'];
        ?>
        <tr>
            <td>
                <a target='_blank' href="video-edit.php?resource_id=<?php echo $row['resource_id'];?>">
                    <?php echo $row['date'];?><br>
                    <?php if(!$row['skip_preview']){ ?>
                        <img src="images/<?php echo $row['preview'];?>"><br>
                    <?php } ?>
                   (редактировать)
                </a>
            </td>
            <td>
                <a target='_blank' href='<?php echo $row['public_url'];?>'><?php echo $row['name'];?></a>
            </td>
            <td>
                <?php echo ($row['type']=='common')?"Видео":"Приятные моменты";?>
            </td>
            <td>
                <div class='col-sm-9'>
                    <div>
                        <?php
                        foreach ($tags as $tag) {
                            if(is_array($assignedTags) && in_array($tag['id'], $assignedTags)) {
                                ?>
                                <a href="tag.php?id=<?php echo $tag['id']; ?>"><?php echo $tag['title']; ?></a>,
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </td>
            <td>
                <input type="hidden" name="resource_id[]" value="<?php echo $row['resource_id'];?>">
                <div class='col-sm-9'>
                    <div>
                        <?php
                            $tag_input_name = 'tags_new['. $row['resource_id'] .'][]';
                        ?>
                        <select class='form-select' id='validationTagsNewSame' name='<?php echo $tag_input_name;?>' multiple data-allow-new='true' data-allow-same='true'>
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
        <?php
    }
    ?>
    </tbody>
</table>
</form>
    <?php
}
?>