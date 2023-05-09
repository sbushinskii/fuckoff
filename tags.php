<?php

require_once 'database.php';
require_once 'functions.php';
$db = new Database();
$tags = $db->getTags();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Мои метки</title>
</head>
<body>
    <h1>Мои видео метки</h1>
    <?php require_once 'nav.php';?>
    <?php
        foreach ($tags as $tag) { ?>
            <a href="tag.php?id=<?php echo $tag['id'];?>"><?php echo $tag['title'];?> (<?php echo $tag['counter'];?>)</a><br>
    <?php
        }
    ?>
</body>
</html>

