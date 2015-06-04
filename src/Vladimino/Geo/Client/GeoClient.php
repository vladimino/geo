<?php

namespace Vladimino\Geo\Client;

use Vladimino\Geo\Console\Input;
use Vladimino\Geo\Console\Output;
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
        "h",
        "v"
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
        "help",
        "version"
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
     * @return string
     */
    public function getLocation()
    {
        return $this->sLocation;
    }

    /**
     * @param string $sLocation
     */
    public function setLocation($sLocation)
    {
        $this->sLocation = $sLocation;
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return $this->sProviderName;
    }

    /**
     * @param string $sProviderName
     */
    public function setProviderName($sProviderName)
    {
        $this->sProviderName = $sProviderName;
    }

    /**
     * Processes given options from arguments
     */
    protected function handleRequest()
    {
        /** @var array $aOptions */
        $aOptions = Input::getPassedOptions($this->aShortOptions, $this->aLongOptions);

        if (!empty($aOptions)) {
            if (isset($aOptions['h']) || isset($aOptions['help'])) {
                $this->setCommand('help');
                return;
            }

            if (isset($aOptions['v']) || isset($aOptions['version'])) {
                $this->setCommand('version');
                return;
            }

            if (isset($aOptions['location'])) {
                $this->setCommand('geocode');
                $this->sLocation = $aOptions['location'];
            }

            if (isset($aOptions['provider'])) {
                $this->sProviderName = $aOptions['provider'];
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
            case 'version':
                break;
            default:
                $this->printAbout();
        }
    }


    /**
     * Trying to geocode given location
     *
     * @return \Vladimino\Geo\Entity\ResultCollection
     * @throws \Exception if can't get results
     */
    protected function getResultsByLocation()
    {
        try {
            $this->oResultCollection = $this->getProviderObject()->getResultsByLocation($this->getLocation());
            return $this->oResultCollection;
        } catch (\Exception $e) {
            $this->terminateApplication($e->getMessage());
        }
    }

    /**
     * Prints geocoding results
     */
    protected function printGeoResults()
    {
        /** @var string $sOutput */
        $sOutput = "";

        if ($this->oResultCollection) {
            $sOutput .= sprintf("Given Location '{purple}%s{/purple}' with provider '{purple}%s{/purple}'.\n", $this->oResultCollection->sLocation, $this->oResultCollection->sProvider);
            $sOutput .= sprintf("Found {cyan}%d{/cyan} result(s).\n\n", $this->oResultCollection->iCount);

            /** @var \Vladimino\Geo\Entity\Result $oResult */
            foreach ($this->oResultCollection as $oResult) {
                $sOutput .= sprintf("* {yellow}Result #%d:{/yellow}\n", $this->oResultCollection->iPosition + 1);
                $sOutput .= sprintf(" ** {brown}Country:{/brown} {green}%s{/green};\n", $oResult->sCountry);
                $sOutput .= sprintf(" ** {brown}State:{/brown} {green}%s{/green};\n", $oResult->sState);
                $sOutput .= sprintf(" ** {brown}City:{/brown} {green}%s{/green};\n", $oResult->sCity);
                $sOutput .= sprintf(" ** {brown}Longitude:{/brown} {green}%s{/green};\n", $oResult->fLongitude);
                $sOutput .= sprintf(" ** {brown}Latitude:{/brown} {green}%s{/green}.\n\n", $oResult->fLatitude);
            }
        } else {
            $sOutput .= sprintf("{yellow}Unfortunately, no results found{/yellow}\n\n");
        }

        Output::printMessage($sOutput);
    }


    /**
     * Returns the long version of the application.
     *
     * @return string The long application version
     */
    protected function printLongVersion()
    {
        Output::printMessage(sprintf("{bold_blue}%s{/bold_blue} version {cyan}%s{/cyan}\n\n", $this->getName(), $this->getVersion()));
    }

    /**
     * Prints information about application
     */
    protected function printAbout()
    {
        Output::printMessage("Type {green}--help{/green} to display all available options.\n\n");
    }

    /**
     * Prints help instructions.
     *
     */
    protected function printHelp()
    {
        /** @var string $sOutput */

        $sOutput = "{yellow}Usage:{/yellow}\n php geo.php [options]\n\n";
        $sOutput .="{yellow}Available options:{/yellow}\n";
        $sOutput .= " {green}--help{/green} (-h)\t\tDisplay this help message.\n";
        $sOutput .= " {green}--version{/green} (-v)\t\tDisplay application version.\n";
        $sOutput .= " {green}--location{/green} {purple}<address>{/purple}\tAddress to geocode.\n";
        $sOutput .= " {green}--provider{/green}{purple} <name>{/purple}\tProvider for geocoding service [{brown}default value:{/brown} {purple}google{/purple}].\n\n";

        Output::printMessage($sOutput);
    }

    /**
     * Creates Provider object if it does not exists
     *
     * @return \Vladimino\Geo\Provider\GeoProviderInterface
     */
    protected function getProviderObject()
    {
        try {
            if (is_null($this->oProvider)) {
                $this->oProvider = GeoProviderFactory::getProvider($this->sProviderName);
            }
            return $this->oProvider;
        } catch (\Exception $e) {
            $this->terminateApplication($e->getMessage());
        }

    }

    /**
     * Terminates application with error
     *
     * @param string $sMessage
     */
    protected function  terminateApplication($sMessage)
    {
        Output::printMessage(sprintf("{bold_red}Application terminated unexpectedly.{/bold_red}\n{bold_red}%s{/bold_red}\n\n", $sMessage));
        die;
    }

} 