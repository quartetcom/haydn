<?php
namespace Quartet\Haydn;

use Quartet\Haydn\IO\ColumnMapper\ChainMapper;
use Quartet\Haydn\IO\ColumnMapper\SimpleArrayColumnMapper;
use Quartet\Haydn\IO\Source\ArraySource;

class ColumnMapChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testChainMapper()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250,300],
            ["お","か", 50,3000],
        ];

        $mapper1 = new SimpleArrayColumnMapper([
            'char1', 'char2', 'num1', 'num2'
        ]);
        $mapper2 = new ChainMapper(
            $mapper1,
            [
                'char1' => 'a-char1',
                'char2' => 'b-char2',
                'num1'  => 'c-num1',
                'num2'  => 'd-num2',
            ]
        );
        $aSource = new ArraySource('array', $data, $mapper2);
        $set = new Set($aSource, 'array');
        $set->setPrefixing(true);

        $result = $set->toArray();

        $this->assertThat($result[0]['array.a-char1'], $this->equalTo('あ'));
        $this->assertThat($result[0]['array.c-num1'], $this->equalTo(150));
        $this->assertThat($result[1]['array.a-char1'], $this->equalTo('う'));
        $this->assertThat($result[1]['array.b-char2'], $this->equalTo('え'));
        $this->assertThat($result[2]['array.a-char1'], $this->equalTo('お'));
        $this->assertThat($result[2]['array.d-num2'], $this->equalTo(3000));
    }
}
