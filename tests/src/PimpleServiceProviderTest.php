<?php
namespace tests;

use Germania\YamlServices\PimpleServiceProvider;
use Pimple\Container;
use Symfony\Component\Finder\Finder;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

class PimpleServiceProviderTest extends \PHPUnit_Framework_TestCase
{




    public function testNoArgumentInstantiation( )
    {
        $container = new Container;

        $sut = new PimpleServiceProvider;
        $sut->register( $container );

        $this->assertInternalType("integer", $container['Yaml.Flags']);
        $this->assertEquals(0, $container['Yaml.Flags']);
        $this->assertInstanceOf( Finder::class, $container['Yaml.Finder']);
        $this->assertInstanceOf( LoggerInterface::class, $container['Yaml.Logger']);
        $this->assertTrue( is_callable($container['Yaml.Parser'] ));
        $this->assertTrue( is_callable($container['Yaml.FileParser'] ));

    }

    /**
     * @dataProvider providerCtorArguments
     */
    public function testYamlFlagsAreInteger( $flags, $finder, $logger )
    {
        $container = new Container;

        $sut = new PimpleServiceProvider( $flags, $finder, $logger);
        $sut->register( $container );

        $this->assertInternalType("integer", $container['Yaml.Flags']);
        $this->assertEquals($flags, $container['Yaml.Flags']);
    }


    /**
     * @dataProvider providerCtorArguments
     */
    public function testYamlFinderIsSymfonyFinder( $flags, $finder, $logger )
    {
        $container = new Container;

        $sut = new PimpleServiceProvider( $flags, $finder, $logger);
        $sut->register( $container );

        $this->assertInstanceOf( Finder::class, $container['Yaml.Finder']);
    }

    /**
     * @dataProvider providerCtorArguments
     */
    public function testYamlLoggerIsLoggerInterface( $flags, $finder, $logger )
    {
        $container = new Container;

        $sut = new PimpleServiceProvider( $flags, $finder, $logger);
        $sut->register( $container );

        $this->assertInstanceOf( LoggerInterface::class, $container['Yaml.Logger']);
    }

    /**
     * @dataProvider providerCtorArguments
     */
    public function testYamlParsersAreCallable( $flags, $finder, $logger )
    {
        $container = new Container;

        $sut = new PimpleServiceProvider( $flags, $finder, $logger);
        $sut->register( $container );

        $this->assertTrue( is_callable($container['Yaml.Parser'] ));
        $this->assertTrue( is_callable($container['Yaml.FileParser'] ));
    }






    public function providerCtorArguments()
    {
        $finder = $this->prophesize(Finder::class);
        $finder_mock = $finder->reveal();

        $logger = $this->prophesize(LoggerInterface::class);
        $logger_mock = $logger->reveal();

        return array(
            [ 0, new Finder, new NullLogger],
            [ 14, $finder_mock, $logger_mock]
        );
    }
}
