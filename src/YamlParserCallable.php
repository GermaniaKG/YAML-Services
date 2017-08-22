<?php
namespace Germania\YamlServices;

use Symfony\Component\Yaml\Yaml;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class YamlParserCallable
{


    /**
     * @var integer
     */
    public $default_yaml_flags = 0;


    /**
     * @var LoggerInterface
     */
    public $logger;


    /**
     * @param integer $default_yaml_flags  Default parsing flags, if needed.
     * @param LoggerInterface $logger      Optional PSR3 Logger
     */
    public function __construct( $default_yaml_flags = 0, LoggerInterface $logger = null)
    {
        $this->default_yaml_flags = $default_yaml_flags;
        $this->logger = $logger ?: new NullLogger;
    }


    /**
     * @param  string   $yaml_content The YAML content to parse
     * @param  int|null $yaml_flags   Custom YAML flags
     * @return string
     */
    public function __invoke( $yaml_content, $yaml_flags = null )
    {
        $flags = is_integer($yaml_flags) ? $yaml_flags : $this->default_yaml_flags;

        $this->logger->info("Parse YAML content", [
            'flags' => $flags
        ]);

        return Yaml::parse($yaml_content, $flags);
    }
}
