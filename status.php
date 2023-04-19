<?php
require_once 'functions.php';
$disk = new Disk();
$disk->downloadErrors();
echo "Count Vids: " . countVids();
echo "<br><br>";

echo "Errors:<br>";
listErrors();