<?php
$tags = $db->getTags();
if(empty($vids)){
    return false;
}
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
if($order == 'DESC') {
    $order = 'ASC';
} else {
    $order = 'DESC';
}
?>

<div>
    <table class="table table-striped">
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
            <th>Инфо</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($vids as $row) {
            $assignedTags = $row['assignedTags'];
            $row = $row['video'];
            ?>
            <tr>
                <td class="col-6">
                    <?php
                    $preview = "/images/".$row['preview'];
                    $show_preview = file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $preview) && !is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $preview);

                    echo $row['date'];
                    $preview_file =  IMG_DIR . $row['preview'];

                    ?><br>

                    <a target='_blank' href='<?php echo $row['public_url'];?>' title="Смотреть на Диске">
                        <?php if(!is_dir($preview_file) && file_exists($preview_file)){ ?>
                            <img height="150px" src="images/<?php echo $row['preview'];?>"><br>
                        <?php }else{ ?>
                            <img height="150px" src="assets/nophoto.jpg"><br>
                        <?php }?>
                    </a>
                </td>
                <td  class="col-6">
                    Заголовок: <?php echo $row['name'];?><br>
                    Категория: <?php echo ($row['type']=='common')?"Видео":"Приятные моменты";?><br>
                    Мише: <?php echo $row['Misha'];?><br>
                    Вере: <?php echo $row['Vera'];?><br>

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
                    <div>
                        <a target='_blank' href="video-edit.php?resource_id=<?php echo $row['resource_id'];?>">
                            <button class='btn btn-primary btn-primary ' type='button'>Редактировать</button>
                        </a>
                        <a target='_blank' href='<?php echo $row['public_url'];?>' title="Смотреть на Диске">
                            <button class='btn btn-primary btn-info' type='button'>Cмотреть</button>
                        </a>
                        <button class='btn btn-primary btn-danger' onclick="return removeVideo('<?php echo $row['resource_id'];?>')"  type='button'>Удалить</button>
                    </div>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function removeVideo(resource_id){
        if(confirm("Уверен?")){
            document.location='video-delete.php?resource_id='+resource_id
        }
        return false;
    }
</script>