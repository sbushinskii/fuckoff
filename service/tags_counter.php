<?php
require_once '../database.php';
$db = new Database();
$db->recalculateTagsUsage();

echo "Теги просчитаны";
echo "<br>";
echo "<a href='/service/index.php'>ОК</a>";
