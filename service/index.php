<html>
<head>
    <?php require_once '../header.php';?>
</head>
<body>
<div >
    <?php require_once '../nav.php';?>
    <h1>Сервисное обслуживание</h1>

    <table class="table table-striped table-bordered">
        <tbody>
            <tr>
                <td>
                    <button class='btn btn-primary' onclick="document.location='/service/db-export.php'"  type='submit'>Экспорт БД</button>
                </td>
                <td>
                    <button class='btn btn-primary' onclick="document.location='/service/scan.php'" type='submit'>Обновить из облака</button>
                </td>
                <td>
                    <button class='btn btn-primary' onclick="document.location='/service/tags_counter.php'" type='submit'>Пересчет использования тегов</button>
                </td>
                <td>
                    <button class='btn btn-primary' onclick="document.location='/service/tags_counter.php'" type='submit'>Пересчет использования тегов</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>

