<?php
namespace tests;

use Germania\YamlServices\YamlParserCallable;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;


class YamlParserCallableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideCtorArguments
     */
    public function testInstantiation( $flags, $logger )
    {
        $sut = new YamlParserCallable( $flags, $logger );
        $sut = new YamlParserCallable( $flags );
        $sut = new YamlParserCallable( );
        $this->assertTrue( is_callable( $sut ));
    }


    /**
     * @dataProvider provideParsingData
     */
    public function testParsingFlagsOnCtor($data, $flags, $data_type)
    {
        $yaml_data = Yaml::dump($data);

        $sut = new YamlParserCallable( $flags );
        $result = $sut($yaml_data);
        $this->assertInternalType( $data_type, $result);
    }


    /**
     * @dataProvider provideParsingData
     */
    public function testParsingFlagsOnInvokation($data, $flags, $data_type)
    {
        $yaml_data = Yaml::dump($data);

        $sut = new YamlParserCallable;
        $result = $sut($yaml_data, $flags);
        $this->assertInternalType( $data_type, $result);
    }


    public function provideParsingData()
    {
        $data = array('foo' => 'bar', 'number' => 42, 'sub' => array('user' => 'doe'));

        return [
            [ $data, 0, "array" ],
            [ $data, Yaml::PARSE_OBJECT_FOR_MAP, "object" ]
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

