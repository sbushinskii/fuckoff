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
    <?php require_once 'header.php';?>
</head>
<body>
    <?php require_once 'nav.php';?>
    <h1>Мои метки</h1>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th style='width:150px;'>Название</th>
            <th style='width:450px;'>Действие</th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($tags as $tag) { ?>
                <tr>
                    <td>
                        <a href="tag.php?id=<?php echo $tag['id'];?>"><?php echo $tag['title'];?> (<?php echo $tag['counter'];?>)</a><br>
                    </td>
                    <td>
                        <button class='btn btn-primary' onclick="return editTag(<?php echo $tag['id'];?>)" type='button'>Редактировать</button>
                        <button class='btn btn-primary btn-danger' onclick="return removeTag(<?php echo $tag['id'];?>)" type='button'>Удалить</button>
                    </td>
                </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <script type="text/javascript">
        function removeTag(id){
            if(confirm("Уверен?")){
                document.location='tag-delete.php?id=' + id;
            }
            return false;
        }
        function editTag(id){
            document.location='tag-edit.php?id=' + id;
        }
    </script>
</body>
</html>

