<?php if(!empty($vids)){ ?>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th style='width:150px;'>Дата</th>
        <th style='width:50px;'>Название</th>
        <th style='width:50px;'>Категория</th>
        <th style='width:450px;'>Метки</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($vids as $row) {
        $assignedTags = $row['tags'];
        $row = $row['video'];
        ?>
        <tr>
            <td>
                <a target='_blank' href="video-edit.php?resource_id=<?php echo $row['resource_id'];?>">
                    <?php echo $row['date'];?><br>
                    <img src="images/<?php echo $row['preview'];?>"><br>
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
                        <?php foreach ($tags as $tag) {
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
        </tr>
        <?php
    }
    ?>
    </tbody>
</table>

    <?php
}
?>