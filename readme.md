# Запуск сервера
docker start
docker run -it \
    --link  work-mysql:db \
    -p 9300:9300 \
    -v '/Users/user/PhpstormProjects:/var/www' \
    -w '/var/www/slack.qualitycase.ru' \
    --rm php:5.6-cli-alpine sh

php -S 0.0.0.0:9300 -t public/

# Ипортируем список пользователей
php artisan import:members --filename=users.json

# База данных
php artisan make:migration create_members_table --create=members
php artisan make:migration create_channels_table --create=channels
php artisan make:migration create_messages_table --create=messages

# Список пользователей
https://slack.com/api/conversations.members?token=xoxp-310706314629-315468234226-337432270294-f16323c3c4865fc86c59088efe63c924&channel=C93THQMMW&pretty=1

{
    "ok": true,
    "members": [
        "U94H68GJ0",
        "U94LS98MT",
        "U94S8M3QQ",
        "U95EF8G03",
        "U95UXAU72",
        "U95V9AC3C",
        "U9606TPMY",
        "U966PTWD9",
        "U96DDMSRJ",
        "U96E0MBV2",
        "U96H2HS4A",
        "U96KHGXF0",
        "U96UTSQPR",
        "U974M969G",
        "U97ALMHFS",
        "U97JPGWMU",
        "U97PA7DLK",
        "U97REFYET",
        "U989GL3RQ",
        "U98SXKWJD",
        "U993BJ2MP",
        "U997MBF0W",
        "U99DS6W6N",
        "U99E1C092",
        "U99E9Q2P2",
        "U99JAC8SD",
        "U99UE3G5P",
        "U9AURAG1E",
        "U9B19DC9H",
        "U9BPX5JCE",
        "U9GFZQXD4"
    ],
    "response_metadata": {
        "next_cursor": ""
    }
}

#Список личных сообщений
- Перечисляет прямые каналы сообщений для вызывающего пользователя.
https://slack.com/api/im.list?token=xoxp-310706314629-315468234226-337432270294-f16323c3c4865fc86c59088efe63c924&pretty=1

# Открываем канал
https://slack.com/api/im.open?token=xoxp-310706314629-315468234226-337432270294-f16323c3c4865fc86c59088efe63c924&user=U94H68GJ0&pretty=1

# Читаем сообщения
https://slack.com/api/im.history?token=xoxp-310706314629-315468234226-337432270294-f16323c3c4865fc86c59088efe63c924&channel=D9FH2ES95&count=1000&pretty=1