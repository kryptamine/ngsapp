# Тестовое задание

При выполнении тестового задания было решено придерживаться бизнес модели(mvc).
*Почему?*
Люблю такое разделение логики и считаю его максимально верным, легко масштабируемым, 
когда приложение разбито на отдельные реюзабильные компоненты - это здорово.
Сразу хочу отметить: у меня не было никакого опыта работы с MongoDB и больше 
всего времени я потерял на составлении аггрегирующего запроса(об этом позднее).
Данные rest api я решил парсить через curl.
Погоду в выбранном регионе(точнее alias города), я посчитал правильным сохранять в куки, 
для каждого посетителя, неплохо было бы шифровать эти куки, чтобы обезопасить себя от подмены.
По порядку:
- index.php - корневой файл приложения, содержит в себе ключевую точку входа, с помощью spl_autoload_register,
 подгружаем все файлы приложения, в соответствии с неймспейсами.
- update.php - файл, который должен запускаться по cron, раз например в 30 минут, получает данные о текущей погоде 
и влажности(с учетом городов, которые есть в базе на данный момент), далее по этим данным мы формируем архив 
погоды за прошедшие 3 дня, т.к. эти показатели отсутствуют в REST API, мы будем генерировать их самостоятельно, на основе
средних показателей, собранных за 3 дня по каждому из городов.
команда: 
```sh
cron */30 * * * * /usr/local/bin/php /var/www/update.php
```
- /base/ - абстрактные классы-родители(Model, View, Controller)
- /data/ - файлы mongodb
- /core/db/db.php - написал лёгкую обёртку для работы с базой данных.
Файловая структура приложения:
- core/http/ - небольшой роутинг, организующий доступ к соответствующим контроллерам и их методам
- /core/curl.php - написал небольшую curl-обёртку, для лёгкого и непринужденного парсинга с rest api
Думаю папки bower_components,controllers,models и views пояснения не требуют.
Основная логика приложения находится в /controllers/CityController.php

Примечание: т.к. это тестовое приложение, данные о погоде каждый раз подгружаются 
с вашего API, в реальном приложении, их лучше записывать в базу и периодически обновлять.
Я понимаю, что приложение получилось немного громоздким и можно было всё решить в 3 файла, но захотелось сделать как-то так.

#Установка
1. Склонировать репозиторий
2. Обратите внимание на наличие .htaccess, настройки для nginx:
# nginx configuration 
    
``location /
{
    rewrite ^/$ /index.php break;
    if (!-e $request_filename)
    {
        rewrite ^(.*)$ /index.php;
    } 
}``
3. Ссылка на приложение(относительно корня): http://localhost/city

#Требования
 - libcurl
 - mongodb
 - php 5+


