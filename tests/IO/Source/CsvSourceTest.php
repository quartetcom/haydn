<?php
namespace Quartet\Haydn\IO\Source;

use Quartet\Common\CsvUtil\Csv;

class CsvSourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function test()
    {
        $source = new CsvSource('foo', new Csv(__DIR__.'/data/test.csv'));
        $this->assertThat($source, $this->isInstanceOf(CsvSource::class));
    }

    /**
     * @test
     */
    public function columnName()
    {
        $source = new CsvSource('foo', new Csv(__DIR__.'/data/test.csv'));

        $source->setColumnsFromRow(0);
        $source->setPrefixing(true);

        $result = [];
        foreach ($source as $line) {
            $result[] = $line;
        }

        $this->assertThat($result[0]['foo.あ'], $this->equalTo(1));
        $this->assertThat($result[0]['foo.name'], $this->equalTo(3));
        $this->assertThat($result[1]['foo.い'], $this->equalTo(5));
        $this->assertThat($result[1]['foo.address'], $this->equalTo(8));
    }
}
