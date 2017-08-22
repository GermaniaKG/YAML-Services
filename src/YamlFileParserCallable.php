<?php
namespace Germania\YamlServices;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class YamlFileParserCallable
{


    /**
     * @var integer
     */
    public $default_yaml_flags = 0;


    /**
     * @var Finder
     */
    public $finder;


    /**
     * @var LoggerInterface
     */
    public $logger;


    /**
     * @param Finder  $finder             Symfony Finder instance
     * @param integer $default_yaml_flags Default parsing flags, if needed.
     * @param LoggerInterface $logger     Optional PSR3 Logger
     */
    public function __construct( Finder $finder, $default_yaml_flags = 0, LoggerInterface $logger = null)
    {
        $this->finder = $finder;
        $this->default_yaml_flags = $default_yaml_flags;
        $this->logger = $logger ?: new NullLogger;
    }


    /**
     * @param  string   $yaml_file    The YAML file to parse
     * @param  int|null $yaml_flags   Custom YAML flags
     * @return string
     */
    public function __invoke( $yaml_file, $yaml_flags = null )
    {
        $finder = clone $this->finder;
        $finder = $finder->files()->name( $yaml_file );

        // If there's no such file
        if (!iterator_count($finder)):
            $this->logger->debug("Could not find YAML file", [
                'file' => $yaml_file
            ]);
            return null;
        endif;

        // Reset finder instance
        $iterator = $finder->getIterator();
        $iterator->rewind();

        // Retrieve first item
        $file = $iterator->current();
        $yaml_content = $file->getContents();

        // How to parse
        $flags = is_integer($yaml_flags) ? $yaml_flags : $this->default_yaml_flags;

        // Some info
        $this->logger->info("Parse YAML file " . $file->getFilename(), [
            'file' => $file->getPathname(),
            'flags' => $flags
        ]);

        // Parse and return
        return Yaml::parse($yaml_content, $flags);
    }
}
