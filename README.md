Для удаления броней, у которых дата окончания подошла к концу, следует добавить в cron эту запись
    * * * * * php /path/to/artisan schedule:run > /dev/null 2>&1


Документация API находится по ссылке APP_URL/api/documentation