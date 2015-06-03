<?php
namespace Vladimino\Geo\Provider;

/**
 * This class implements factory for different GeoProviderInterface implementations.
 * It takes provider name as a parameter which defines what exactly implementation requested.
 *
 * @author vladimino
 */
class GeoProviderFactory
{
    /**
     * Prefix for the class name (namespace)
     */
    const CLASS_PREFIX = "Vladimino\\Geo\\Provider\\";
    /**
     * Suffix for the class name
     */
    const CLASS_SUFFIX = "GeoProvider";

    /**
     * Known implementations of Geo Providers
     *
     * @var array
     */
    private static $aProviderNameToClass = array(
        "google" => "Google",
    );

    /**
     * Main and only method to get an instance of GeoProviderInterface implementation
     * by given name.
     *
     * @static
     *
     * @param string $sProviderName
     *
     * @return \Vladimino\Geo\Provider\GeoProviderInterface
     * @throws \InvalidArgumentException if unknown provider name given
     * @throws \RuntimeException if can't found declared class
     * @throws \RuntimeException if class does not implement HotelServiceInterface
     */
    public static function getProvider($sProviderName)
    {
        if (!isset(self::$aProviderNameToClass[$sProviderName])) {
            throw new \InvalidArgumentException(sprintf( "%s Error. Unknown provider: '%s'.", __CLASS__ , $sProviderName));
        }

        /** @var string $sProviderClassname */
        $sProviderClassname = self::CLASS_PREFIX . self::$aProviderNameToClass[$sProviderName] . self::CLASS_SUFFIX;

        if (!class_exists($sProviderClassname)) {
            throw new \RuntimeException(sprintf("%s Error. Can't load class for given provider '%s' with classname '%s'.", __CLASS__ , $sProviderName, $sProviderClassname));
        }

        /** @var \Vladimino\Geo\Provider\GeoProviderInterface  $oProvider */
        $oProvider = new $sProviderClassname();

        if (!($oProvider instanceof GeoProviderInterface)) {
            throw new \RuntimeException(sprintf("%s Error. Classname '%s' does not implement GeoProviderInterface.", __CLASS__ , $sProviderClassname));
        }

        return $oProvider;
    }
}
