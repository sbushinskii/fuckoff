<!doctype html>
<html lang="en">
<head>
    <title>Видосы</title>
    <?php require_once '../header.php';?>
</head>
<body>
<section class="ftco-section">
    <div class="container">
        <div class="justify-content-center">
            <div >
                <?php require_once '../nav.php';?>
                <h1>Сервисное обслуживание</h1>
                <div>
                    <table class="table table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td>
                                <button class='btn btn-primary' onclick="document.location='/service/db-export.php'"  type='submit'>Экспорт БД</button>
                            </td>
                            <td>
                                <button class='btn btn-primary' onclick="document.location='/service/scan.php?mode=light'" type='submit'>Обновить недавнее</button>
                            </td>
                            <td>
                                <button class='btn btn-primary' onclick="document.location='/service/scan.php'" type='submit'>Обновить все</button>
                            </td>
                            <td>
                                <button class='btn btn-primary' onclick="document.location='/service/tags_counter.php'" type='submit'>Пересчет использования тегов</button>
                            </td>
                            <td>
                                <button class='btn btn-primary' onclick="document.location='/service/photos.php'" type='submit'>Скачать иконки фото</button>
                            </td>
                            <td>
                                <button class='btn btn-primary' onclick="document.location='/service/last_moments_import.php'" type='submit'>Последний импорт моментов когда был</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>

