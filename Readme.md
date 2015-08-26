# Tech Test from Itembase

This is a toy project to get an expirience with command line applications.

## Initial Specification

Please write PHP CLI object oriented application to fulfill the following needs.

Application should accept 2 parameters: `geolocation provider` and `location`. Example of the usage:

    $ php geo.cli -provider=“google” -location=“Oranienstraße 164, 10969, Berlin Germany”

Establishing connection to `geolocation provider` API and get information about `location`. 
Application should be designed in the way so it can use multiple geolocation provider APIs and implementation of the new `geolocation provider` is programmer-friendly. 
Please do Google Geocoding API as an example implementation. 
Don’t implement the whole Google Geocoding API. Just the part for getting the information about `location`.

The response should be unified, so the same structure no matter what provider was used. It should contain:

* “country”
* “state”
* “city”
* “longitude”
* “latitude”

API can return more than one result so you need to handle and display that as well.

## Requirements

* PHP 5.4 or higher
* Composer to install some dependencies.
* Some kind of shell

## Installation

1. Download the [`composer.phar`](https://getcomposer.org/composer.phar) executable or use the installer.

    ```
    $ curl -sS https://getcomposer.org/installer | php
    ```

2. Install required libraries and create autoload.php:

    ```
    $ php composer.phar install
    ```

## Usage

1. Change current directory for simpler command line commands:

    ```
    $ cd src/Vladimino/Geo/app/
    ```
 
2. Run application:

    ```
    $ php geo.php [OPTIONS]
    ```

### Available options:

     --help (-h)            Display this help message.
     --version (-v)         Display application version.
     --location <address>   Address to geocode.
     --provider <name>      Provider for geocoding service [default value: google].
     --format <stdout|json> Return format [default value: stdout].

## Author

Volodymyr Bilokur  - <vladimino@gmail.com> - <https://twitter.com/vladimino>

## License

Distributed under the MIT License, see the [LICENSE](https://raw.github.com/vladimino/geo/master/LICENSE) file for details. 
