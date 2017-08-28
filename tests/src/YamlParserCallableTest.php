<?php
namespace tests;

use Germania\YamlServices\YamlParserCallable;
use Pimple\Container;
use Symfony\Component\Finder\Finder;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

class YamlParserCallableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideCtorArguments
     */
    public function testInstantiation( $flags, $logger )
    {
        $sut = new YamlParserCallable( $flags, $logger );

    }



    public function provideCtorArguments()
    {
        $logger = $this->prophesize(LoggerInterface::class);
        $logger_mock = $logger->reveal();

        return [
            [ 0, $logger_mock ],
            [ 14, $logger_mock ]
        ];
    }
}

