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
$tags = $db->getTags();
?>

<!doctype html>
<html lang="en">
<head>
    <title>Видео по меткам</title>
    <?php require_once 'header.php';?>
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="justify-content-center">
            <div >
                <?php require_once 'nav.php';?>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h1>Тэг "<?php echo $tag['title'];?>"</h1>
                    <a href="tag-edit.php?id=<?php echo $tag['id'];?>">Редактировать</a><br>

                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style='width:150px;'>Название</th>
                            <th style='width:450px;'>Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($vids as $row) {
                            $assignedTags = $db->getVideoTagsIds($row['resource_id']);
                            $preview = "/images/".$row['preview'];
                            $show_preview = file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $preview) && !is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $preview);
                            ?>
                            <tr>
                                <td>
                                    <a target='_blank' href='<?php echo $row['public_url'];?>' title="Смотреть на Диске">
                                        <?php if($show_preview){ ?>
                                            <img height="150px" src="images/<?php echo $row['preview'];?>"><br>
                                        <?php }else{ ?>
                                            <img height="150px" src="assets/nophoto.jpg"><br>
                                        <?php }?>
                                    </a>

                                    <a target='_blank' href='<?php echo $row['public_url'];?>'><?php echo $row['name'];?></a>
                                    <br>
                                    <a href="video-edit.php?resource_id=<?php echo $row['resource_id'];?>">(редактировать)</a><br>
                                    <?php echo $row['date']; ?><br>
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
                    <script type="text/javascript">
                        function removeTag(id){
                            if(confirm("Уверен?")){
                                document.location='tag-delete.php?id=' + id;
                            }
                            return false;
                        }
                        function editTag(id){
                            document.location='tag-edit.php?id=' + id;
                        }
                    </script>
                </div>
            </div>
        </div>
</section>
<button onclick="document.location='index.php?send=true'" class='btn btn-primary' type='submit'>Отправить в телеграм</button>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
