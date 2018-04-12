## Запуск сервера
#### Через терминал
php -S localhost:9093 -t public_html/
#### Через  openserver
Нужно настроить папку домена - public_html/
## Настройка файла конфигурации,uploads? дамб базы данных, миграции
####  Конфигурация config.php
Зайти в папку **app/core** и вставить конфиги базы данных из **config-ex.php** в **сonfig.php** изменив настройки подключения к mysql серверу, при этом имя базы данных нужно **обязательно** оставить таким же, для корректной работы.
####  Создать папку uploads
Создайте папку uploads в public_html
####  Дамп базы данных
В папке **migrations** находится дамп базы данных mysql, импортировать данную базу к себе любым способом.
####  Создание таблиц в БД
В папке app/models/migrations находится файл с запуском миграций(migrations.php), для созданий таблиц с нужными полями.Запустить.
####  Инициализация первичными данными(опционально)
В папке app/models/faker находится файл faker.php, для инициализации таблиц первичными данными.