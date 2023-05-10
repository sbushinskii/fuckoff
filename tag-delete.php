<?php
require_once 'database.php';
require_once 'functions.php';
$db = new Database();

if(!empty($_GET['id'])) {
    $tag_id = $_GET['id'];
    $tag = $db->getTag($tag_id);
    if(!empty($tag)) {
        $db->delete('tags', 'id', $tag_id);
        $db->delete('video_tag', 'tag_id', $tag_id);
        $message = "Метка \"".$tag['title']."\" удалена";
    } else {
        $message = "Метка не найдена";
    }
} else {
    $message = "Неверный запрос";
}
?>


<html lang="en">
<head>
    <title>Удалить метку</title>
    <?php include 'header.php';?>
</head>
<body>
    <h1><?php echo $message;?></h1>
    <a href='tags.php'>Назад</a>
</body>
</html>