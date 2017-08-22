<?php
namespace Germania\YamlServices;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

use Symfony\Component\Finder\Finder;

class PimpleServiceProvider implements ServiceProviderInterface
{

    /**
     * @var integer
     */
    public $yaml_flags = 0;


    /**
     * @var Finder
     */
    public $finder;


    /**
     * @var LoggerInterface
     */
    public $logger;


    /**
     * @param integer $yaml_flags  YAML Parser flags. Defaults to 0.
     * @param Finder $finder       Optional Symfony Finder instance
     * @param Finder $finder       Optional PSR3 Logger
     */
    public function __construct( $yaml_flags = 0, Finder $finder = null, LoggerInterface $logger = null)
    {
        $this->yaml_flags = $yaml_flags;
        $this->finder = $finder ?: new Finder;
        $this->logger = $logger ?: new NullLogger;
    }


    /**
     * @implements ServiceProviderInterface
     */
    public function register(Container $dic)
    {


        $dic['Yaml.Flags'] = function($dic) {
            return $this->yaml_flags;
        };


        $dic['Yaml.Finder'] = function($dic) {
            return $this->finder;
        };


        $dic['Yaml.Logger'] = function($dic) {
            return $this->logger;
        };


        $dic['Yaml.Parser'] = function($dic) {
            $flags = $dic['Yaml.Flags'];
            $logger = $dic['Yaml.Logger'];
            return new YamlParserCallable( $flags, $logger );
        };


        $dic['Yaml.FileParser'] = function( $dic ) {
            $finder = $dic['Yaml.Finder'];
            $flags  = $dic['Yaml.Flags'];
            $logger = $dic['Yaml.Logger'];
            return new YamlFileParserCallable( $finder, $flags, $logger );
        };



    }
}
