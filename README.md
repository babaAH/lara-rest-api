1. Сперва необходимо создать .env-файл <br>
`cp .env.example .env `
<p>далее в файле .env необходимо изменить значения DB_HOST, DB_DATABASE, DB_PASSWORD, APP_URL</p>

2. Далее необходимо в контейнере app установить ключ приложения <br>
`php artisan key:generate` 

3. <p>Далее необходимо в контейнере app накатиить миграции:</p> <br>
`php artisan migrate`

Для удаления броней, у которых дата окончания подошла к концу, следует добавить в cron эту запись
    <br>
    * * * * * php /path/to/artisan schedule:run > /dev/null 2>&1


Документация API находится по ссылке APP_URL/api/documentation