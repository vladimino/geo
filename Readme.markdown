# Tech Test from Itembase
This is a test project within Itembase's technical recruiting process.

## Specification

Please write PHP CLI object oriented application to fulfill the following needs.

Application should accept 2 parameters: “geolocation provider” and “location”. Example of the usage:

php geo.cli -provider=“google” -location=“Oranienstraße 164, 10969, Berlin Germany”

Establishing connection to “geolocation provider” API and get information about “location”. Application should be designed in the way so it can use multiple geolocation provider APIs and implementation of the new “geolocation provider” is programmer-friendly. Please do Google Geocoding API as an example implementation. Don’t implement the whole Google Geocoding API. Just the part for getting the information about “location”.

The response should be unified, so the same structure no matter what provider was used. It should contain:

* “country”
* “state”
* “city”
* “longitude”
* “latitude”

API can return more than one result so you need to handle and display that as well.

## Preconditions

You need at least:

* PHP 5.4 or higher
* Composer to install some dependencies. See [this documentation to get started with composer](http://getcomposer.org/doc/00-intro.md#installation)
* Some kind of shell, if you want to run unit tests