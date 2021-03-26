## Requirements
PHP ImageMagick required for images processing. Read more about installation [here](http://php.net/manual/en/imagick.installation.php)

## Installation

`composer install`

`cp .env.example .env`

`php artisan key:generate`

## Usage example

`php -dmemory_limit=5G artisan download:daysTime 24.02.2017 09.11.2017 15:30 --filename=15.30 --format=gif -p filterEmpty -p resize -p timestamp`
