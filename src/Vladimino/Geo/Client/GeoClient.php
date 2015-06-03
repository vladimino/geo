<?php

namespace Vladimino\Geo\Client;

use Vladimino\Geo\Console\EscapeColors;
use Vladimino\Geo\Provider\GeoProviderFactory;

/**
 * Class GeoClient
 *
 * @package Vladimino\Geo\Client
 * @author vladimino
 */
class GeoClient
{
    /**
     * Version
     */
    const VERSION = '0.1';

    /**
     * Version
     */
    const NAME = 'GeoCoder Console Application';

    // TODO: Implement Getter & Setter

    /**
     * @var string
     */
    protected $sProviderName = 'google';

    // TODO: Implement Getter & Setter

    /**
     * @var string
     */
    protected $sLocation;

    /**
     * @var \Vladimino\Geo\Provider\GeoProviderInterface
     */
    protected $oProvider;

    /**
     * @var \Vladimino\Geo\Entity\ResultCollection
     */
    protected $oResultCollection;

    /**
     * Allowed short options
     *
     * @var array
     */
    protected $aShortOptions = [
        "l:",
        "p:",
        "f:",
        "h"
    ];

    /**
     * Allowed long options
     *
     * @var array
     */
    protected $aLongOptions = [
        "location:",
        "provider:",
        "format:", // TODO: Implement Format handling
        "help"
    ];

    /**
     * @var string
     */
    protected $sCommand;

    public function __construct()
    {
        $this->handleRequest();
    }

    /**
     * Gets the name of the application.
     *
     * @return string The application name
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Gets the application version.
     *
     * @return string The application version
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Gets given command
     *
     * @return string|null Given command
     */
    protected function getCommand()
    {
        return $this->sCommand;
    }

    /**
     * @param string $sCommand
     */
    protected function setCommand($sCommand)
    {
        $this->sCommand = $sCommand;
    }

    /**
     * @return \Vladimino\Geo\Provider\GeoProviderInterface
     */
    private function getProviderObject()
    {
        try {
            if (is_null($this->oProvider)) {
                $this->oProvider = GeoProviderFactory::getProvider($this->sProviderName);
            }
            return $this->oProvider;
        } catch (\Exception $e) {
            $this->terminate($e->getMessage());
        }

    }

    /**
     * @return \Vladimino\Geo\Entity\ResultCollection
     * @throws \Exception if can't get results
     */
    public function getResultsByLocation()
    {
        try {
            $this->oResultCollection = $this->getProviderObject()->getResultsByLocation($this->sLocation);
            return $this->oResultCollection;
        } catch (\Exception $e) {
            $this->terminate($e->getMessage());
        }
    }

    protected function printGeoResults()
    {

        // TODO: Implement enviroment check
        //if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        //    echo 'This is a server using Windows!';
        //} else {
        //    echo 'This is a server not using Windows!';
        //}

        if ($this->oResultCollection) {
            printf("Given Location '%s' with provider '%s'.\n", $this->oResultCollection->sLocation, $this->oResultCollection->sProvider);
            printf("Found %d result(s).\n\n", $this->oResultCollection->iCount);

            /** @var \Vladimino\Geo\Entity\Result $oResult */
            foreach ($this->oResultCollection as $oResult) {
                printf("* Result #%d:\n", $this->oResultCollection->iPosition + 1);
                printf("\t** Country: %s;\n", $oResult->sCountry);
                printf("\t** State: %s;\n", $oResult->sState);
                printf("\t** City: %s;\n", $oResult->sCity);
                printf("\t** Longitude: %s;\n", $oResult->fLongitude);
                printf("\t** Latitude: %s.\n\n", $oResult->fLatitude);
            }

        } else {
            print "Unfortunately, no results found\n";
        }
    }

    /**
     * Returns the long version of the application.
     *
     * @return string The long application version
     */
    public function printLongVersion()
    {
        //return EscapeColors::green($this->getName()) . " version " . EscapeColors::cyan($this->getVersion()) . "\n";
        printf("%s version %s\n\n", $this->getName(), $this->getVersion());
    }

    /**
     * Processes given options from arguments
     */
    protected function handleRequest()
    {
        /** @var array $aOptions */
        $aOptions = getopt(implode($this->aShortOptions), $this->aLongOptions);

        if (!empty($aOptions)) {
            if (isset($aOptions['h']) || isset($aOptions['help'])) {
                $this->setCommand('help');
                return;
            }

            if (isset($aOptions['l']) || isset($aOptions['location'])) {
                $this->setCommand('geocode');
                $this->sLocation = (isset($aOptions['l'])) ? $aOptions['l'] : $aOptions['location'];
            }

            if (isset($aOptions['p']) || isset($aOptions['provider'])) {
                $this->sProviderName = (isset($aOptions['p'])) ? $aOptions['p'] : $aOptions['provider'];
            }
        }
    }

    /**
     * Executes given command and prints results
     */
    public function executeCommand()
    {
        $this->printLongVersion();

        switch ($this->getCommand()) {
            case 'geocode':
                $this->getResultsByLocation();
                $this->printGeoResults();
                break;
            case 'help':
                $this->printHelp();
                break;
            default:
                $this->printAbout();
        }
    }

    /**
     * Prints information about application
     */
    protected function printAbout()
    {
        print "Type --help to display all possible options.\n";
    }

    /**
     * Prints help instructions.
     *
     */
    protected function printHelp()
    {
        print "Possible options:\n";
        print "--help, -h: Display this help message.\n";
        print "--location <address>, -l<address>: Address to geocode.\n";
        print "--provider <name>, -p<name>: Provider for geocoding service [Default: google].\n";
        print "\nExample usage:\n\n";
        print "php geo.php --location \"Oranienstraße 164, 10969, Berlin Germany\" --provider google\n\n";
    }

    /**
     * Terminate application with error
     *
     * @param string $sMessage
     */
    protected function  terminate($sMessage)
    {
        die(sprintf("Application terminated unexpectedly.\n%s\n", $sMessage));
    }

} 