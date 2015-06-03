<?php

namespace Vladimino\Geo\Client;

use Vladimino\Geo\Provider\GeoProviderFactory;

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
    protected $sProviderName;

    /**
     * @var \Vladimino\Geo\Provider\GeoProviderInterface;
     */
    protected $oProvider;

    /**
     * @var \Vladimino\Geo\Entity\ResultCollection;
     */
    protected $oResultCollection;

    private $foreground_colors = array();
    private $background_colors = array();

    /**
     *
     */
    public function __construct()
    {
        $this->foreground_colors['black'] = '0;30';
        $this->foreground_colors['dark_gray'] = '1;30';
        $this->foreground_colors['blue'] = '0;34';
        $this->foreground_colors['light_blue'] = '1;34';
        $this->foreground_colors['green'] = '0;32';
        $this->foreground_colors['light_green'] = '1;32';
        $this->foreground_colors['cyan'] = '0;36';
        $this->foreground_colors['light_cyan'] = '1;36';
        $this->foreground_colors['red'] = '0;31';
        $this->foreground_colors['light_red'] = '1;31';
        $this->foreground_colors['purple'] = '0;35';
        $this->foreground_colors['light_purple'] = '1;35';
        $this->foreground_colors['brown'] = '0;33';
        $this->foreground_colors['yellow'] = '1;33';
        $this->foreground_colors['light_gray'] = '0;37';
        $this->foreground_colors['white'] = '1;37';

        $this->background_colors['black'] = '40';
        $this->background_colors['red'] = '41';
        $this->background_colors['green'] = '42';
        $this->background_colors['yellow'] = '43';
        $this->background_colors['blue'] = '44';
        $this->background_colors['magenta'] = '45';
        $this->background_colors['cyan'] = '46';
        $this->background_colors['light_gray'] = '47';
        $this->handleRequest();
    }

    /**
     * Returns colored string
     */

    public function getColoredString($string, $foreground_color = null, $background_color = null)
    {
        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .= $string . "\033[0m";

        return $colored_string;
    }

    // Returns all foreground color names
    public function getForegroundColors()
    {
        return array_keys($this->foreground_colors);
    }

    // Returns all background color names
    public function getBackgroundColors()
    {
        return array_keys($this->background_colors);
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
            die($e->getMessage());
        }

    }

    /**
     * @param string $sLocation
     *
     * @return \Vladimino\Geo\Entity\ResultCollection
     */
    public function getResultsByLocation($sLocation)
    {
        $this->oResultCollection = $this->getProviderObject()->getResultsByLocation($sLocation);
        return $this->oResultCollection;
    }

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
     *
     * @api
     */
    public function getLongVersion()
    {
        return $this->getColoredString($this->getName(), 'green') . ' version ' . $this->getColoredString($this->getVersion(), 'cyan') . "\n";
    }

    protected function handleRequest()
    {
        print $this->getLongVersion();
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

} 