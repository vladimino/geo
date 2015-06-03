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

    /**
     * @var string
     */
    protected $sProviderName = 'google';

    /**
     * @var \Vladimino\Geo\Provider\GeoProviderInterface;
     */
    protected $oProvider;

    /**
     * @var \Vladimino\Geo\Entity\ResultCollection;
     */
    protected $oResultCollection;

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
     *
     * @api
     */
    public function getVersion()
    {
        return self::VERSION;
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
     * @param string $sLocation
     *
     * @return \Vladimino\Geo\Entity\ResultCollection
     */
    public function getResultsByLocation($sLocation)
    {
        try {
            $this->oResultCollection = $this->getProviderObject()->getResultsByLocation($sLocation);
            return $this->oResultCollection;
        }
        catch (\Exception $e)
        {
            $this->terminate($e->getMessage());
        }
    }

    /**
     * Prints the output.
     */
    public function printResults()
    {

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
            echo "Unfortunately, no results found\n";
        }
    }

    /**
     * Returns the long version of the application.
     *
     * @return string The long application version
     */
    public function getLongVersion()
    {
        //return EscapeColors::green($this->getName()) . " version " . EscapeColors::cyan($this->getVersion()) . "\n";
        return sprintf("%s version %s\n", $this->getName() , $this->getVersion());
    }

    /**
     *
     */
    protected function handleRequest()
    {
        //print $this->getLongVersion();
    }

    /**
     * Gets the default input definition.
     *
     */
    protected function printHelp()
    {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--quiet', '-q', InputOption::VALUE_NONE, 'Do not output any message'),
            new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
            new InputOption('--ansi', '', InputOption::VALUE_NONE, 'Force ANSI output'),
            new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
            new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ));
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