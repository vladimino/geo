# Tech Test from Itembase
This is a test project within Itembase's technical recruiting process.

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

## Questions & Answers

* *Can I change the way options are passed to application?* 
    > **Itembase:** It is up to you, just mention this on notes.

* *Could you please specify output format? May I use colorful console output?* 
    > **Itembase:** It is up to you, do your best.

* *Am I allowed to use any cool 3rd-party libraries for this project?* 
    > **Itembase:** No, please use pure PHP, we want to see how you design the application.

## Notes

Well, if something returns to me with a comment "up to you", I always want to do everything maximal ideal and flexible.
I guess it's called "perfectionism" :)

So now you have pretty powerful command line tool with help, versioning, error handling, colorful output (in non-Windows environment), 
simple configuring for new geolocation providers and few available output formats (human readable console and ready for machine processing json).
Some PHPUnit tests are also present (unfortunately, had not enough time to cover all the code).

And yes, I've changed the way in which arguments should be passed to the application into Unix conventions-style followed in PHP community (see **Usage** section).  
  
The only thing which I did against the rules is requiring `symfony/yaml` via `composer.json`. 
The reason behind this is simple: I wanted to store provider's configurations in this simple and powerful format, but PHP
does not have any built-in functions to work with YML. So when I was forced to choose between [PECL extension](https://pecl.php.net/package/yaml)
and [Symfony Component](http://symfony.com/doc/current/components/yaml/introduction.html) for me it was the easy one decision. 
Especially if I anyway designed application structure with Packagist/Symfony Bundle suggested style and used Composer's functionality to autoload classes.
 
I know there are some things that could be refactored (personally me don't like final GeoClient class as it brakes Separation of Concerns principle and should be 
split at least into 2 classes), somewhere chosen solutions definitely looks like overhead. But I decided to stop trying to find a silver bullet, and just let you 
look at the result of my 3 days work. It could be improved for sure in the next iterations, but I hope at least you've got an idea.
  
Will wait for some criticism and/or (why not?) positive feedback. 

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