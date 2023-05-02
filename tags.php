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
    <title>Tags</title>
</head>
<body>
    <h1>Мои видео метки</h1>

    <?php
        foreach ($tags as $tag) { ?>
            <a href="get_videos.php?tag_id=<?php echo $tag['id'];?>"><?php echo $tag['title'];?> (<?php echo $tag['counter'];?>)</a><br>
    <?php
        }
    ?>
</body>
</html>

