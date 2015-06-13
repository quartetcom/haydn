<?php
namespace Quartet\Haydn\Set;

use Quartet\Haydn\IO\Source\SingleColumnArraySource;
use Quartet\Haydn\Set;

class EmptySetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function emptyProductTest()
    {
        $setA = new Set(new SingleColumnArraySource('a', ['abc','def']));
        $i = new EmptySet();

        $all = $setA->product($i)->toArray();

        $this->assertThat($all, $this->equalTo([]));

        $all = $i->product($setA)->toArray();

        $this->assertThat($all, $this->equalTo([]));
    }
}
