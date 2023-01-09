[![SWUbanner](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://supportukrainenow.org/)

<img alt="GitHub" src="https://img.shields.io/github/license/ruslandem/evemapper"> <img alt="GitHub top language" src="https://img.shields.io/github/languages/top/ruslandem/evemapper"> <img alt="GitHub language count" src="https://img.shields.io/github/languages/count/ruslandem/evemapper"> <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/ruslandem/evemapper">

# EveMapper

EveMapper is a signature logging, route planner and solar system mapping tool in the Eve Online game.

## Requirements

* PHP 8+
* Nginx 1.2+

See `/install` for docker-compose and nginx configuration files.

## Installation

1. Clone repository.

```bash
git clone https://github.com/ruslandem/evemapper
```

2. Correct file permissions if necessary:

```bash
export GIT_WORK_TREE=<YOU PATH TO FILE>
export WWW_USER=www-data
sudo chown -R $WWW_USER:$WWW_USER $GIT_WORK_TREE`
```

3. Install depencies (for production):

```bash
composer install --no-dev --optimize-autoloader --quiet
npm install --quiet --no-progress --omit=dev
npm run build
```

4. Compose database (`--force` required when running in production mode):

```bash
php artisan migrate:install --quiet --force
php artisan migrate --quiet --force
```

5. Start micro-services by following the procedure in `docker-compose.xml`.

6. Configuration.
   * Back-end configuration file: `.env` (see `.env.example`).
   * Front-end configuration file: `resourses/assets/js/config.ts` (see comments in the file). 

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
