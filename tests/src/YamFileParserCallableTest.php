<?php
namespace tests;

use Germania\YamlServices\YamlFileParserCallable;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;

class YamlFileParserCallableTest extends \PHPUnit_Framework_TestCase
{
    public $finder;
    public $data_file = 'sample.yaml';

    public function setUp()
    {
        $this->finder = new Finder;
        $this->finder->files()->in( realpath( __DIR__ . '/../mocks'));
    }


    /**
     * @dataProvider provideCtorArguments
     */
    public function testInstantiation( $flags, $logger )
    {
        $sut = new YamlFileParserCallable( $this->finder, $flags, $logger );
        $sut = new YamlFileParserCallable( $this->finder, $flags );
        $sut = new YamlFileParserCallable( $this->finder );
        $this->assertTrue( is_callable( $sut ));
    }


    /**
     * @dataProvider provideParsingData
     */
    public function testParsingFlagsOnCtor( $flags, $data_type)
    {
        $sut = new YamlFileParserCallable( $this->finder, $flags );
        $result = $sut($this->data_file );
        $this->assertInternalType( $data_type, $result);
    }


    /**
     * @dataProvider provideParsingData
     */
    public function testParsingFlagsOnInvokation($flags, $data_type)
    {
        $sut = new YamlFileParserCallable( $this->finder );
        $result = $sut($this->data_file, $flags);
        $this->assertInternalType( $data_type, $result);
    }

    /**
     * @dataProvider provideParsingData
     */
    public function testNullResultOnFileNotFound($flags, $data_type)
    {
        $sut = new YamlFileParserCallable( $this->finder );
        $result = $sut("Not_a_file", $flags);
        $this->assertNull( $result);
    }


    public function provideParsingData()
    {
        return [
            [ 0, "array" ],
            [ Yaml::PARSE_OBJECT_FOR_MAP, "object" ]
        ];
    }


    public function provideCtorArguments()
    {
        $logger = $this->prophesize(LoggerInterface::class);
        $logger_mock = $logger->reveal();

        return [
            [ 0, $logger_mock ],
            [ 23, $logger_mock ]
        ];
    }
}

