<?php
namespace Quartet\Haydn\IO\Source;

class ArraySourceTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $source = new ArraySource([
            [1,2,3],
            [4,5,6]
        ]);
        $this->assertThat($source, $this->isInstanceOf(ArraySource::class));
    }
}
