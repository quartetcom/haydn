<?php
namespace Quartet\Haydn\IO\Source;

use Quartet\Common\CsvUtil\Csv;

class CsvSourceTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $source = new CsvSource(new Csv(__FILE__));
        $this->assertThat($source, $this->isInstanceOf(CsvSource::class));
    }
}
