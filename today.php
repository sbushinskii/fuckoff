<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();

if(isset($_GET['send'])){
    sendTodayVideos($all = true);
}

?>
<html>
<head>
    <?php require_once 'header.php';?>
</head>
<body>
<div >
<?php require_once 'nav.php';?>
<h1>Видео Сегодня</h1>



<table class="table table-striped table-bordered">
<thead>
<tr>
    <th style='width:150px;'>Дата</th>
    <th style='width:50px;'>Название</th>
    <th style='width:450px;'>Метки</th>
</tr>
</thead>
<tbody>
<?php
    $filter = ' WHERE unique_date = "'.getUniqueDate(date('Y-m-d')).'"';

	$result_count = mysqli_query($db->con,"SELECT COUNT(*) As total_records FROM `videos`".$filter);
	$total_records = mysqli_fetch_array($result_count);

	$total_records = $total_records['total_records'];

    $result = mysqli_query($db->con,"SELECT * FROM `videos` $filter");
    $tags = $db->getTags();


    while($row = mysqli_fetch_array($result)){
        $assignedTags = $db->getVideoTagsIds($row['resource_id']);
        ?>
            <tr>
                    <td>
                        <?php echo $row['date'];?><br>
                        <a target='_blank' href="video-edit.php?resource_id=<?php echo $row['resource_id'];?>">(редактировать)</a>
                    </td>
                    <td>
                        <a target='_blank' href='<?php echo $row['public_url'];?>'><?php echo $row['name'];?></a>
                    </td>
                <td>
                    <div class='col-sm-9'>
                        <div>
                            <?php foreach ($tags as $tag) {
                                if(in_array($tag['id'], $assignedTags)) {
                                    ?>
                                    <a href="tag.php?id=<?php echo $tag['id']; ?>"><?php echo $tag['title']; ?></a>,
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </td>
          </tr>
    <?php
        }
	mysqli_close($db->con);
    ?>
</tbody>
</table>

    <button onclick="document.location='today.php?send=true'" class='btn btn-primary' type='submit'>Отправить в телеграм</button>

</div>
</body>
</html>