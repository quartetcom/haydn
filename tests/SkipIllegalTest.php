<?php
namespace Quartet\Haydn;

use Quartet\Haydn\IO\ColumnMapper\ChainMapper;
use Quartet\Haydn\IO\ColumnMapper\SimpleArrayColumnMapper;
use Quartet\Haydn\IO\Source\ArraySource;

class SkipIllegalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testSkipIllegal()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250],
            ["お","か", 50,3000],
        ];

        $mapper1 = new SimpleArrayColumnMapper([
            'char1', 'char2', 'num1', 'num2'
        ]);

        $aSource = new ArraySource('array', $data, $mapper1);
        $set = new Set($aSource, 'array');
        $set->setPrefixing(true);

        $result = $set->toArray();

        $this->assertThat($result[0]['array.char1'], $this->equalTo('あ'));
        $this->assertThat($result[0]['array.num1'], $this->equalTo(150));
        $this->assertThat($result[1]['array.char1'], $this->equalTo('お'));
        $this->assertThat($result[1]['array.num2'], $this->equalTo(3000));
    }

    /**
     * @test
     */
    public function testSkipIllegal_setFalse()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250],
            ["お","か", 50,3000],
        ];

        $mapper1 = new SimpleArrayColumnMapper([
            'char1', 'char2', 'num1', 'num2'
        ]);

        $aSource = new ArraySource('array', $data, $mapper1);
        $aSource->setSkipIllegalRow(false);
        $set = new Set($aSource, 'array');
        $set->setPrefixing(true);

        $this->setExpectedException('\RuntimeException');

        $result = $set->toArray();
    }
}
