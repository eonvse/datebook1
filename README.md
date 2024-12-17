# Datebook v0.1.0
## Развертывание
```
git clone https://github.com/eonvse/datebook1
cd datebook1
cp .env.example .env
composer install
node install
```
Настройте подключение к БД.

```
php artisan key:generate
php artisan migrate
php artisan db:seed --class=RolesSeeder
php artisan db:seed --class=TeamsSeeder
php artisan storage:link
```

## License
[MIT license](https://opensource.org/licenses/MIT)

* [Laravel 11](https://laravel.com/docs/11.x)
    * [Laravel Sail (Docker)](https://laravel.com/docs/11.x/sail#main-content)
    * [Laravel Jetstream](https://jetstream.laravel.com/introduction.html)
    * [Livewire Volt](https://livewire.laravel.com/docs/volt)
    * [Filamentphp](https://filamentphp.com)
