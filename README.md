Для работы с sqlite в директории database нужно создать файл database.sqlite
Далее создаем файл конфига cp .env.example .env
В данном файле в директиве DB_CONNECTION следует указать sqlite (либо другую конфигурацию бд)

Далее выполняем следующую последовательность команд:
composer install
php artisan key:generate
php artisan migrate

Для каталога storage нужны права на запись
Внутренний сервер можно запустить командой php artisan serve
