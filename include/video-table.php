<?php

$tags = $db->getTags();
?>

<?php if(!empty($vids)){
    $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
    if($order == 'DESC') {
        $order = 'ASC';
    } else {
        $order = 'DESC';
    }
?>

<form class='needs-validation' method='POST'>
<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Дата
            <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>?order=<?php echo $order;?>" class="text-decoration-none">
                <?php if($order == 'ASC') { ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16">
                    <path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"></path>
                </svg>
        <?php } else { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-down" viewBox="0 0 16 16">
                        <path d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/>
                    </svg>
            <?php
            }
            ?>
            </a>
        </th>
        <th>Изображение</th>
        <th>Смотреть на диске</th>
        <th>Категория</th>
        <th>Метки</th>
        <th>Тэги</th>
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
                <?php echo $row['date'];?><br>
                <a target='_blank' href="video-edit.php?resource_id=<?php echo $row['resource_id'];?>">
                    редактировать<br><br>
                </a>
            </td>
            <td>
                <?php
                    $preview = "/images/".$row['preview'];
                    $skip_preview = true;
                    if(file_exists($preview)){
                        $skip_preview = false;
                    }
                ?>
                <?php if(!$skip_preview || !$row['skip_preview']){ ?>
                <a target='_blank' href='<?php echo $row['public_url'];?>'>
                    <img height="150px" src="images/<?php echo $row['preview'];?>"><br>
                </a>
                <?php } ?>
                <input type="file" name="preview">
            </td>
            <td>
                <a target='_blank' href='<?php echo $row['public_url'];?>'><?php echo $row['name'];?></a>
                <br>
                Мише - <?php echo $row['Misha'];?><br>
                Вере - <?php echo $row['Vera'];?><br>
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