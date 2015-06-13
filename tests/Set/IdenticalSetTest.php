<?php
namespace Quartet\Haydn\Set;

use Quartet\Haydn\IO\Source\SingleColumnArraySource;
use Quartet\Haydn\Set;

class IdenticalSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function identicalProductTest()
    {
        $setA = new Set(new SingleColumnArraySource('a', ['abc','def']));
        $i = new IdenticalSet();

        $all = $setA->product($i)->toArray();

        $this->assertThat($all, $this->equalTo([
            ['a' => 'abc'],
            ['a' => 'def']
        ]));

        $all = $i->product($setA)->toArray();

        $this->assertThat($all, $this->equalTo([
            ['a' => 'abc'],
            ['a' => 'def']
        ]));
    }
}
