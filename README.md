## Installation
You can install this package by opening the `composer.json` file
then add the following config:

### Install with Git (config)
"repositories": [
  {
    "url": "https://github.com/dhofa/laravel-service-repository-generator.git",
    "type": "git"
  }
]

### Install from Local (config)
"repositories": [
    {
        "type": "path",
        "url": "YOUR_PATH/laravel-service-repository-generator"
    }
],

After that, install the package:
composer require "dhofa/laravel-service-repository-generator @dev"


## Usage
You can use this package by running the following command:
php artisan make:service-repository ModelName
