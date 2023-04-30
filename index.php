<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'database.php';
$db = new Database();

if(!empty($_POST)) {
    $video_id = $_POST['resource_id'];
    //$db->clearTags($video_id);

    //save tags
    $tag_ids = [];
    foreach ($_POST['tags_new'] as $tag_name) {
        if(!(int)$tag_name+0){
            $record = [
                'title'=>$tag_name
            ];
            $tag_id = $db->insert('tags',$record);
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



?>
<html>
<head>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <script src="assets/js/bootstrap.bundle.min.js" type="module"></script>
    <script src="assets/js/Sortable.min.js" type="module"></script>
    <script src="assets/js/bs-companion.min.js" type="module"></script>
    <script type="module">
        import Tags from "./assets/js/tags.js";

        // These need to be define before init
        window["customItemFormat"] = function (item, label) {
            return label + " (" + item.value + ")";
        };
        window["customOnCreate"] = function (option, inst) {
            console.log("new item", option.value, inst);
            // attach tooltip dynamically
            option.setAttribute('title', 'this element is created dynamically');
        };


        // Multiple inits should not matter
        Tags.init("select:not(.ignore-tags)");

        document.addEventListener("change", (event) => {
            console.log(`document listener: #${event.target.id}`);
        });

        document.querySelector("#show_suggestions").addEventListener("click", (ev) => {
            ev.preventDefault();

            const el = document.getElementById("inputGroupTags");
            /** @type {Tags} */
            let inst = Tags.getInstance(el);
            inst.toggleSuggestions(false);
        });

        document.querySelector('#add_option').addEventListener("click", (ev) => {
            ev.preventDefault();

            const input = document.getElementById('new_option');
            const v = input.value;
            if(!v) {
                alert('No value');
                return;
            }
            const el = document.getElementById('validationTagsNew');
            const inst = Tags.getInstance(el);
            inst.addItem(v);
        })

        FormValidator.init();
    </script>

</head>
<body>
<div >

<h3>Мои Видео</h3>
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th style='width:50px;'>ID</th>
    <th style='width:150px;'>Дата</th>
    <th style='width:50px;'>Название</th>
    <th style='width:150px;'>Ссылка</th>
    <th style='width:450px;'>Тэги</th>
</tr>
</thead>
<tbody>
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

	$result_count = mysqli_query($db->con,"SELECT COUNT(*) As total_records FROM `videos`");
	$total_records = mysqli_fetch_array($result_count);

	$total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
	$second_last = $total_no_of_pages - 1; // total page minus 1

    $result = mysqli_query($db->con,"SELECT * FROM `videos`  LIMIT $offset, $total_records_per_page");
    $tags = $db->getTags();

    while($row = mysqli_fetch_array($result)){
        $assignedTags = $db->getVideoTagsIds($row['resource_id']);
        ?>
		<tr>
			    <td><?php echo $row['id'];?></td>
                <td><?php echo $row['date'];?></td>
                <td><?php echo $row['name'];?></td>
		   	    <td><a target='_blank' href='<?php echo $row['public_url'];?>'>открыть</a></td>

             <td>
		   	  <form class='needs-validation' method='POST'>
                  <input type="hidden" name="resource_id" value="<?php echo $row['resource_id'];?>">
                    <div class='row mb-3 g-3'>
                      <div>
                        <label for='validationTagsNewSame' class='form-label'>Тэг</label>
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
                  <button class='btn btn-primary' type='submit'>Сохранить</button>
              </form>
            </td>
      </tr>
    <?php
        }
	mysqli_close($db->con);
    ?>
</tbody>
</table>

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
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
		echo "<li><a>...</a></li>";
		echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
		echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
		}

	 elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
		echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
           if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a>...</a></li>";
	   echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
	   echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
            }
		
		else {
        echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";

        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
          if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                   
                }
            }
	}
?>
    
	<li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page'"; } ?>>Next</a>
	</li>
    <?php if($page_no < $total_no_of_pages){
		echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
		} ?>
</ul>


</div>
</body>
</html>