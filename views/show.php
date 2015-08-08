<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>NGS | Add</title>
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
    <h1><?=$city['name']?></h1>
    <div class="row">
        <div class="col-md-4">
            <span class="label label-default">Прогноз на 3 дня</span>
            <table class="table">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Температура</th>
                    <th>Влажность</th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($forecast as $day): ?>
                    <tr>
                        <td><?=date('d.m.Y', strtotime($day['date']))?></td>
                        <td><strong><?=$day['temperature']>0 ? '+' : '-' ?><?=$day['temperature'] ?></strong></td>
                        <td><?=$day['humidity'] ?>%</td>

                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <span class="label label-default">Архив погоды</span>
            <table class="table">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Температура</th>
                    <th>Влажность</th>
                </tr>
                </thead>
                <tbody>
                <? if ($archive): ?>
                    <? foreach ($archive['result'] as $data):?>


                        <tr>
                            <td><?=$data['_id']['day'].'.'.$data['_id']['month'].'.'.$data['_id']['year']?></td>
                            <td>
                                <strong><?=$data['averageTemp']>0 ? '+' : '-' ?><?=round($data['averageTemp'],0) ?></strong>
                            </td>
                            <td><?=$data['averageHum'] ?>%</td>
                        </tr>
                    <? endforeach; ?>
                <? endif; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
</body>
</html>
