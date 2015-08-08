<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>NGS | Add</title>
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
        $(function() {
            var availableTags = <?=$cities?>;
            $( "#cityname" ).autocomplete({
                source: availableTags
            });
        });
    </script>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/city/">Weather</a>
        <? if ($weather) : ?>
            <p class="navbar-text"><?=$weather['city']?>:
                Температура:<?=$weather['weather']['temperature']?>&degС
                Давление:<?=$weather['weather']['pressure']?>мм рт.ст.
                Влажность:<?=$weather['weather']['humidity']?>%
            </p>
        <? endif; ?>
    </div>
</nav>
<div class="container" style="margin-top: 60px;">
    <div class="row">
        <div class="bs-example" data-example-id="basic-forms">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="cityname">Название города</label>
                    <input name="name" class="form-control" id="cityname" placeholder="Например: Новосибирск" type="text">
                </div>
                <div class="form-group">
                    <label for="cityalias">Алиас города</label>
                    <input name="alias" class="form-control" id="cityalias" placeholder="Например: nsk" type="text">
                </div>
                <button type="submit" class="btn btn-default pull-right">Добавить</button>
            </form>
        </div>

    </div>
</div>
</body>
</html>
