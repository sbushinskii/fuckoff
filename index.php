<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
require_once 'functions.php';
$db = new Database();
checkPostStatus();
?>
<html>
<head>
    <?php require_once 'header.php';?>
</head>
<body>
<div >
    <?php require_once 'nav.php';?>
<h1>Все видео</h1>
<h4>
    <a href="?type=common">Видео</a>
</h4>
<h4>
    <a href="?type=moments">Приятные моменты</a>
</h4>
<h4>
    <a href="?no_tags=1">Без меток</a>
</h4>

<?php

if (isset($_GET['page_no']) && $_GET['page_no']!="") {
	$page_no = $_GET['page_no'];
	} else {
		$page_no = 1;
        }

	$total_records_per_page = 20;
    $offset = ($page_no-1) * $total_records_per_page;
	$previous_page = $page_no - 1;
	$next_page = $page_no + 1;
	$adjacents = "2";

    $filter = '';
    $type='';

    if(isset($_GET['no_tags'])){
        $sql_exec = "SELECT * FROM videos where resource_id  NOT IN (select DISTINCT(video_id) FROM video_tag) order by `timestamp` DESC LIMIT $offset, $total_records_per_page";
        $result = mysqli_query($db->con, $sql_exec);
    } else {
        if (isset($_GET['type']) && trim($_GET['type'])) {
            $type = $_GET['type'];
            $filter = " WHERE type='$type'";
        }

        $result_count = mysqli_query($db->con, "SELECT COUNT(*) As total_records FROM `videos`" . $filter);
        $total_records = mysqli_fetch_array($result_count);

        $total_records = $total_records['total_records'];
        $total_no_of_pages = ceil($total_records / $total_records_per_page);
        $second_last = $total_no_of_pages - 1; // total page minus 1

        $result = mysqli_query($db->con, "SELECT * FROM `videos` $filter order by `timestamp` DESC LIMIT $offset, $total_records_per_page");
    }

    while($row = mysqli_fetch_array($result)) {
        $assignedTags = $db->getVideoTagsIds($row['resource_id']);
        $vids[] = [
            'video'=>$row,
            'assignedTags' => $assignedTags,
        ];
    }
?>
    <?php require_once 'include/video-table.php';?>


<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
<strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
</div>

<ul class="pagination">
	<?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>
    
	<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>>Previous</a>
	</li>
       
    <?php 
	if ($total_no_of_pages <= 10){  	 
		for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
	}
	elseif($total_no_of_pages > 10){
		
	if($page_no <= 4) {			
	 for ($counter = 1; $counter < 8; $counter++){		 
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?type=$type&page_no=$counter'>$counter</a></li>";
				}
        }
		echo "<li><a>...</a></li>";
		echo "<li><a href='?type=$type&page_no=$second_last'>$second_last</a></li>";
		echo "<li><a href='?type=$type&page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
		}

	 elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
		echo "<li><a href='?type=$type&page_no=1'>1</a></li>";
		echo "<li><a href='?type=$type&page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
           if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?type=$type&page_no=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a>...</a></li>";
	   echo "<li><a href='?type=$type&page_no=$second_last'>$second_last</a></li>";
	   echo "<li><a href='?type=$type&page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
            }
		
		else {
        echo "<li><a href='?type=$type&page_no=1'>1</a></li>";
		echo "<li><a href='?type=$type&page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";

        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
          if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?type=$type&page_no=$counter'>$counter</a></li>";
				}                   
                }
            }
	}
?>
    
	<li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no < $total_no_of_pages) { echo "href='?type=$type&page_no=$next_page'"; } ?>>Next</a>
	</li>
    <?php if($page_no < $total_no_of_pages){
		echo "<li><a href='?type=$type&page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
		} ?>
</ul>


</div>
</body>
</html>