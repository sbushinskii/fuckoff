<?php
require_once 'database.php';
require_once 'functions.php';
$db = new Database();
$formProcessed = false;
if(!empty($_POST)) {
    $video_id = $_POST['resource_id'];
    $db->clearTags($video_id);

    //upload
    if(isset($_FILES["preview"]) && !empty($_FILES["preview"]["tmp_name"])) {
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
    $formProcessed = true;
}


$filter = ' WHERE resource_id = "' . $_GET['resource_id'] . '"';

$result = mysqli_query($db->con, "SELECT * FROM `videos` $filter");
$tags = $db->getTags();
$row = mysqli_fetch_array($result);

?>
<!doctype html>
<html lang="en">
<head>
    <title>Видосы</title>
    <?php require_once 'header.php';?>
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <?php if($formProcessed) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </symbol>
            </svg>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0" style="margin-right: .5rem!important" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                <div>
                    Изменения сохранены
                </div>
            </div>
        <?php
        }
        ?>

        <div class="justify-content-center">
            <div>
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
                <?php require_once 'nav.php';?>
                <h1>Редактирование Видео</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                    <form class='needs-validation' method='POST' enctype="multipart/form-data">
                        <div class="form-group">
                            <a target='_blank' href='<?php echo $row['public_url'];?>'>
                                <?php if(!$row['skip_preview']){ ?>
                                    <img height="150px" src="images/<?php echo $row['preview'];?>"><br>
                                <?php } else { ?>
                                    <img height="150px" src="assets/nophoto.jpg"><br>
                                <?php } ?>
                            </a>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="filename">Название файла</label>
                            <input name="name" class="form-control" id="filename" placeholder="Название файла" value="<?php echo $row['name'];?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Путь в облаке</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" name="path"><?php echo $row['path'];?></textarea>
                            <input type="hidden" name="old_path" value="<?php echo $row['path'];?>">
                        </div>

                        <div class="form-group row col-md-4">
                            <label for="inputState">Тип</label>
                            <select id="inputState" class="form-control ignore-tags" name="type">
                                <option value="common" <?php echo ($row['type']=='common') ? " selected ":""; ?>>Общее</option>
                                <option value="moments" <?php echo ($row['type']=='moments') ? " selected ":""; ?>>Моменты</option>
                            </select>
                        </div>

                        <div class="form-group">
                                <input type="hidden" name="resource_id" value="<?php echo $row['resource_id'];?>">

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
                        <div class="form-group">
                                <button class='btn btn-primary' type='submit'>Сохранить</button>
                                <button class='btn btn-primary btn-danger' onclick="return removeVideo()" type='button'>Удалить</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</section>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

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