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
     * Output Format
     *
     * @var string
     */
    protected $sFormat = "stdout";

    /**
     * Possible values for format
     * @var array
     */
    protected $aAllowedFormats = ["stdout", "json"];

    /**
     * Application command
     *
     * @var string
     */
    protected $sCommand;

    /**
     * Allowed short options
     *
     * @var array
     */
    protected $aShortOptions = ["h", "v"];

    /**
     * Allowed long options
     *
     * @var array
     */
    protected $aLongOptions = [
        "location:",
        "provider:",
        "format:",
        "help",
        "version"
    ];

    /**
     * Stores non-critical errors
     *
     * @var array
     */
    protected $aErrors = [];

    /**
     * @var \Vladimino\Geo\Provider\GeoProviderInterface
     */
    protected $oProvider;

    /**
     * @var \Vladimino\Geo\Entity\ResultCollection
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
     * @return string
     */
    public function getFormat()
    {
        return $this->sFormat;
    }

    /**
     * @param string $sFormat
     */
    public function setFormat($sFormat)
    {
        $this->sFormat = $sFormat;
    }

    /**
     * @param string $sErrorMessage
     */
    public function addError($sErrorMessage){
        $this->aErrors[] = $sErrorMessage;
    }

    /**
     * Processes given options from arguments
     */
    protected function handleRequest()
    {
        /** @var array $aOptions */
        $aOptions = Input::getPassedOptions($this->aShortOptions, $this->aLongOptions);

        if (!empty($aOptions)) {

            if (isset($aOptions['format'])) {
                if (in_array($aOptions['format'], $this->aAllowedFormats)) {
                    $this->setFormat($aOptions['format']);
                } else {
                    $this->addError(sprintf("Unknown format '{purple}%s{/purple}' given.\n", $aOptions['format']));
                    $this->addError(sprintf("Output format is set to default value '{purple}%s{/purple}'.\n", $this->getFormat()));
                    $this->addError(sprintf("{yellow}Allowed formats:{/yellow}\n {green}%s{/green}\n\n", implode(',', $this->aAllowedFormats)));
                }
            }

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
                $this->setLocation($aOptions['location']);
            }

            if (isset($aOptions['provider'])) {
                $this->setProviderName($aOptions['provider']);
            }


        }
    }

    /**
     * Executes given command and prints results
     */
    public function executeCommand()
    {
        if ('stdout' === $this->getFormat()) {

            $this->printLongVersion();
            $this->printErrors();

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

        if ('json' === $this->getFormat()) {
            if ('geocode' === $this->getCommand()) {
                $this->getResultsByLocation();
                $this->responseJSON($this->oResultCollection);
            } else {
                $this->terminateApplication("json format works only for geocode responses");
            }
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
                $sOutput .= sprintf(" * {brown}Country:{/brown} {green}%s{/green};\n", $oResult->sCountry);
                $sOutput .= sprintf(" * {brown}State:{/brown} {green}%s{/green};\n", $oResult->sState);
                $sOutput .= sprintf(" * {brown}City:{/brown} {green}%s{/green};\n", $oResult->sCity);
                $sOutput .= sprintf(" * {brown}Longitude:{/brown} {green}%s{/green};\n", $oResult->fLongitude);
                $sOutput .= sprintf(" * {brown}Latitude:{/brown} {green}%s{/green}.\n\n", $oResult->fLatitude);
            }
        } else {
            $sOutput .= sprintf("{yellow}Unfortunately, no results found{/yellow}\n\n");
        }

        $this->printMessage($sOutput);
    }


    /**
     * Returns the long version of the application.
     *
     * @return string The long application version
     */
    protected function printLongVersion()
    {
        $this->printMessage(sprintf("{bold_blue}%s{/bold_blue} version {cyan}%s{/cyan}\n\n", $this->getName(), $this->getVersion()));
    }

    /**
     * Prints information about application
     */
    protected function printAbout()
    {
        $this->printMessage("Try '{green}php geo.php --help{/green}' for more options.\n\n");
    }

    /**
     * Prints help instructions.
     *
     */
    protected function printHelp()
    {
        /** @var string $sOutput */

        $sOutput = "{yellow}Usage:{/yellow}\n php geo.php [OPTIONS]\n\n";
        $sOutput .= "{yellow}Available options:{/yellow}\n";
        $sOutput .= " {green}--help{/green} (-h)\t\tDisplay this help message.\n";
        $sOutput .= " {green}--version{/green} (-v)\t\tDisplay application version.\n";
        $sOutput .= " {green}--location{/green} {purple}<address>{/purple}\tAddress to geocode.\n";
        $sOutput .= sprintf(" {green}--provider{/green}{purple} <name>{/purple}\tProvider for geocoding service [{brown}default value:{/brown} {purple}%s{/purple}].\n", $this->getProviderName());
        $sOutput .= sprintf(" {green}--format{/green} {purple}<%s>{/purple}\tReturn format [{brown}default value:{/brown} {purple}%s{/purple}].\n\n", implode('|', $this->aAllowedFormats), $this->getFormat());

        $this->printMessage($sOutput);
    }


    /**
     * Prints message according to given format
     *
     * @param string $sMessage
     */
    public function printMessage($sMessage)
    {
        Output::printMessage($sMessage);
    }

    /**
     * Prints response with JSON
     *
     * @param array|object $aValues
     */
    protected function responseJSON($aValues)
    {
        header('Content-Type: application/json');

        /** @var mixed $sJSON */
        $sJSON = json_encode($aValues);

        // If encoding fails
        if (is_null($sJSON)) {
            /** @var array $aResponse */
            $aResponse = [
                'status' => 'error',
                'message' => "Invalid response",
                "provider" => $this->getProviderName(),
                "location" => $this->getLocation()
            ];
            print json_encode($aResponse);
        } else {
            print $sJSON;
        }
        print "\n";
    }

    /**
     * Terminates application with error
     *
     * @param string $sMessage
     */
    protected function  terminateApplication($sMessage)
    {
        if ('stdout' === $this->getFormat()) {
            $this->printMessage(sprintf("{bold_red}Application terminated unexpectedly.{/bold_red}\n{bold_red}%s{/bold_red}\n\n", $sMessage));
        } elseif ('json' === $this->getFormat()) {
            $this->responseJSON([
                'status' => 'error',
                'message' => $sMessage,
                "provider" => $this->getProviderName(),
                "location" => $this->getLocation()
            ]);
        }
        die;
    }



    /**
     * Prints stored non-critical errors
     */
    protected function printErrors()
    {
        if (!empty($this->aErrors)) {
            $this->printMessage(implode($this->aErrors));
            $this->aErrors = [];
        }
    }

} 