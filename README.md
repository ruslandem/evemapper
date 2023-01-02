# EveMapper

EveMapper is an online tool to help you with some aspects of playing the Eve Online game.

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


## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

All rights reserved.
