[![SWUbanner](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://supportukrainenow.org/)

<img alt="GitHub" src="https://img.shields.io/github/license/ruslandem/evemapper"> <img alt="GitHub top language" src="https://img.shields.io/github/languages/top/ruslandem/evemapper"> <img alt="GitHub language count" src="https://img.shields.io/github/languages/count/ruslandem/evemapper"> <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/ruslandem/evemapper">

# EveMapper

EveMapper is a signature logging, route planner and solar system mapping tool in the Eve Online game.

## Requirements

Common:
* PHP 8+
* Nginx

PHP extensions:
- php-mbstring
- php-sqlite3
- php-exif
- php-pcntl
- php-bcmath
- php-gd

See `/install` for docker-compose and nginx configuration files.

## Installation

1. Clone repository.

```bash
git clone https://github.com/ruslandem/evemapper
```

2. Correct permissions and make initial config:

```bash
sudo chown -R www-data:www-data evemapper
cd evemapper
php -r "file_exists('.env') || copy('.env.example', '.env');"
php -r "file_exists('./resources/assets/js/config.ts') || copy('./resources/assets/js/config.example.ts', './resources/assets/js/config.ts');"
```

3. Install depencies:

Composer and npm (for production):

```bash
composer install --no-dev --optimize-autoloader --quiet
npm install --quiet --no-progress --omit=dev
npm run build
```

Generate application key:
```bash
php artisan key:generate
```

4. Compose database (`--force` required when running in production mode):

```bash
php artisan migrate:install
php artisan migrate --force
```

5. Start micro-services by following the procedure in `docker-compose.xml`.

```bash
docker compose up -d --build
```

6. Configuration.

  * Add your Eve Online developer ID and KEY in file `.env`
  * Add (optionally) your character nickname to admins list in files `.env` and `resourses/assets/js/config.ts`
  
(changing the `config.ts` requires running a new build `npm run build`)

See comments in the configuration files for other options.

## Testing

Test environment should use another database (i.e. app_test.db) in the same directory. 

Prior testing, please create separate environment file: 
```bash
cp .env .env.testing
```
and change the next parameters:
```ini
...
APP_ENV=testing
DB_APP=app_test.db
...
```
After you have created the testin environment file as specified above, you can install and run testing database migration:
```bash
touch database/app.db
php artisan migrate:install --env=testing
php artisan migrate --env=testing
```
Finally, you can rpoceed to the testing:
```bash
php artisan test
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](LICENSE) © Ruslan Demchenko
