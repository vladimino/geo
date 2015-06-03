<?php

namespace Vladimino\Geo\Provider;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Class BaseProvider
 *
 * @package Vladimino\Geo\Provider
 * @author vladimino
 */

abstract class BaseProvider
{
    /**
     * Configuration file format.
     */
    const CONFIG_FORMAT = '.yml';

    /**
     * Directory for configuration files
     */
    const CONFIG_DIR = '/../config/';

    /**
     * @var array Configuration values
     */
    protected $aConfig = array();

    /**
     * @var string location
     */
    protected $sLocation;

    /**
     * Configuration loaded while instantiating.
     */
    public function __construct()
    {
        $this->loadConfig();
    }


    /**
     * Returns the name of the Provider.
     * To be implemented in child classes.
     *
     * @return string
     */
    protected abstract function getName();

    /**
     * Returns the name of for the section in configuration file.
     * To be implemented in child classes.
     *
     * @return string
     */
    protected abstract function getConfigSection();

    /**
     * @return string
     */
    protected function buildConfigPath()
    {
        return __DIR__ . self::CONFIG_DIR . $this->getName() . self::CONFIG_FORMAT;
    }

    /**
     * Load configuration file.
     */
    protected function loadConfig()
    {
        /**
         * @var \Symfony\Component\Yaml\Parser
         */
        $oYAML = new Parser();

        /**
         * @var string
         */
        $sConfigPath = $this->buildConfigPath();

        try {

            $aData = $oYAML->parse(file_get_contents($sConfigPath));

        } catch (ParseException $e) {
            printf("Unable to parse the YAML config %s: %s", $sConfigPath, $e->getMessage());
            die;
        }

        /**
         * @var string
         */
        $sConfigSection = $this->getConfigSection();

        if (!isset($aData[$sConfigSection])) {
            printf("No section for '%s' in configuration file: %s", $sConfigSection, $sConfigPath);
            die;
        }

        $this->aConfig = $aData[$sConfigSection];
    }

    /**
     * @param string $sParam
     * @return array|null
     */
    protected function getConfig($sParam = null)
    {
        if (is_null($sParam)) {
            return $this->aConfig;
        }

        if (!isset($this->aConfig[$sParam])) {
            return null;
        }

        return $this->aConfig[$sParam];
    }

    /**
     * Get the location.
     */
    protected function getLocation()
    {
        return $this->sLocation;
    }

    /**
     * Set the location string.
     *
     * Location — The street address that you want to geocode, in the format
     * used by the national postal service of the country concerned.
     *
     * @param string $sLocation
     */
    protected function setLocation($sLocation)
    {
        $this->sLocation = $sLocation;
    }


}