# Задачи
- Добавить таблицу курсов
-- id
-- title
-- chanel_name
-- api_key

# Запуск сервера
docker start work-mysql
docker run -it \
    --link  work-mysql:db \
    -p 9300:9300 \
    -v '/Users/user/PhpstormProjects:/var/www' \
    -w '/var/www/slack.qualitycase.ru' \
    --rm php:5.6-cli-alpine sh

# Подготовка сервера
apk update
apk add php5-mcrypt
apk add php5-pdo_mysql

echo 'extension=/usr/lib/php5/modules/mcrypt.so' >> /usr/local/etc/php/php.ini
echo 'extension=/usr/lib/php5/modules/pdo_mysql.so' >> /usr/local/etc/php/php.ini

#Запуск сервера
php -S 0.0.0.0:9300 -t public/

# Ипортируем список пользователей
php artisan import:members --filename=users.json

# Загружаем сообщения
php artisan load:direct:message

# База данных
php artisan make:migration create_members_table --create=members
php artisan make:migration create_channels_table --create=channels
php artisan make:migration create_messages_table --create=messages

# Список пользователей
https://slack.com/api/conversations.members?token=xoxp-&channel=C93THQMMW&pretty=1

{
    "ok": true,
    "members": [
    ],
    "response_metadata": {
        "next_cursor": ""
    }
}

# список пользователей
https://slack.com/api/im.list?token=" . env("SLACK_TOKEN") . "&pretty=1

#Список личных сообщений
- Перечисляет прямые каналы сообщений для вызывающего пользователя.
https://slack.com/api/im.list?token=xoxp-&pretty=1

# Открываем канал
https://slack.com/api/im.open?token=xoxp-&user=U94H68GJ0&pretty=1

# Читаем сообщения
https://slack.com/api/im.history?token=xoxp-&channel=D9FH2ES95&count=1000&pretty=1

### Тут получаем токен
https://api.slack.com/custom-integrations/legacy-tokens
