## IAnalytics api project

### Description

~~~
This project is a part of IAnalytics project.
- It is an api for IAnalytics project.
- It is based on Laravel 10.31.0
- Database is postgresql 13.0
- It is a dockerized project.
- It is a gitlab ci/cd project.
~~~

### Requirements

~~~
1. Git
2. Gitlab account
3. Gitlab runner server
2. SSH key
3. Docker
~~~

### Installation

~~~
1. Clone this repository: `git clone git@10.50.223.215:developers/ffin/ianalytics-api.git
2. docker-compose build 
3. docker-compose up -d
4. docker-compose exec app composer install
5. docker-compose exec app php artisan key:generate
6. docker-compose exec app php artisan migrate --seed
7. docker-compose exec app php artisan storage:link
8. Open http://localhost:8001/ in your browser to check if it works
~~~

### Most important 3-rd party packages

~~~
- [Pest] - for testing
- [Pest-stress] - for stress testing
- [Laravel cipher sweet] - for encrypting data
- [Jwt-auth] - for authentication
- [Laravel socialite] - for social authentication
- [socialiteproviders/Azure] - for azure authentication
- [Predis] - for redis cache
- [Darkaonline/l5-swagger] - for swagger documentation
- [Laravel ide-helper] - for ide helper
- [Laravel pail] - for logging
- [Laravel translation] - for translation
~~~

## Notes

### Переводы

~~~
 - Если добавить в админ панели по языкам, перевод в KZ, но без EN, то перевод создасться, но на панели не будет видно перевода, т.к 
   главный язык EN по умолчанию. Поэтому всегда нужно добавлять EN, в случае отсутствия.
   
 - Управление админ панелью переводов лежит в vendor/translation оттуда можно смотреть логику.
~~~

### Scout search

~~~
 - Для поиска используется scout, который индексирует данные в elasticsearch. Поэтому для поиска нужно запустить meilisearch (Для локалки он в докере, а для работы на сервере нужно установить его на сервере).
 - Для индексации данных нужно запустить команду php artisan scout:import "App\Models\{ModelName}".
 - Для удаления индекса нужно запустить команду php artisan scout:flush "App\Models\{ModelName}".
 
 Для создания контейнера в докере на сервере нужно прописать
 
 docker run -d --name=meilisearch --restart=always --network=vpa_network -p 7700:7700     
 -v /opt/meilisearch/data:/meili_data     
 -e MEILI_MASTER_KEY=masterKey 
 getmeili/meilisearch
~~~