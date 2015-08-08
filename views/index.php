<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Weather</title>
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css"/>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/city/">Weather</a>
        <p class="navbar-text"><?=$weather['city']?>:
            Температура:<?=$weather['weather']['temperature']?>&degС
            Давление:<?=$weather['weather']['pressure']?>мм рт.ст.
            Влажность:<?=$weather['weather']['humidity']?>%
        </p>
    </div>
</nav>
<div class="container" style="margin-top: 60px;">
    <div class="row">
            <a href="add" class="btn btn-primary">Добавить город</a>
            <table width="100%" class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Город</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?

                $i = 0;
                foreach ($cursor as $key => $city):

                    $i++;

                ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><a href="show/<?=$city['alias']?>"><?=$city['name']?></a></td>
                        <td>
                            <a href="edit/<?=$key?>" class="btn btn-warning">Изменить</a>
                            <a href="delete/<?=$key?>" class="btn btn-danger">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
    </div>
</div>
</body>
</html>
