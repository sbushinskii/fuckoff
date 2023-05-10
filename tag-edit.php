<?php
require_once 'database.php';
require_once 'functions.php';

if(!empty($_POST)) {
    $db = new Database();
    $id = $_POST['id'];
    $db->update('tags','id', $id, 'title', $_POST['title']);
}

$tag_id = isset($_GET['id']) ? $_GET['id'] : false;
if(!(int)$tag_id) {
    echo "Fuck you";
    exit;
} else {
    $db = new Database();
    $tag = $db->getTag($tag_id);
}

?>

<html lang="en">
<head>
    <title>Видео по меткам</title>
    <?php include 'header.php';?>
</head>
<body>
<?php require_once 'nav.php';?>
<h1>Редактирование метки "<?php echo $tag['title'];?>"</h1>

<div>
    <form method="POST">
        <div class="form-group">
            <label for="exampleInputEmail1">Название</label>
            <input class="form-control" name="title" placeholder="Название" value="<?php echo $tag['title'];?>">
            <input type="hidden" name="id" value="<?php echo $tag['id'];?>">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>
</body>
</html>
