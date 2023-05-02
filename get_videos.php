<?php
require_once 'database.php';
require_once 'functions.php';

$tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : false;
if(!(int)$tag_id) {
    echo "Fuck you";
    exit;
} else {
    $db = new Database();
    $tag = $db->getTag($tag_id);
    $vids = $db->findVideosByTag($tag_id);
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<h1>Тэг "<?php echo $tag['title'];?>"</h1>

<?php foreach ($vids as $vid){ ?>
    <a target="_blank" href="<?php echo $vid['public_url'];?>"><?php echo $vid['name'];?></a><br>
<?php
}
?>
</body>
</html>
