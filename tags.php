<?php

require_once 'database.php';
require_once 'functions.php';
$db = new Database();
$tags = $db->getTags();
?>
<!doctype html>
<html lang="en">
<head>
    <title>Видосы</title>
    <?php require_once 'header.php';?>
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="justify-content-center">
            <div >
                <?php require_once 'nav.php';?>
            </div>

            <div class="row">
                <div class="col-md-12">
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
                </div>
            </div>
        </div>
</section>
<button onclick="document.location='index.php?send=true'" class='btn btn-primary' type='submit'>Отправить в телеграм</button>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
