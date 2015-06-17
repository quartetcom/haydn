<?php
namespace Quartet\Haydn\Set;

use Quartet\Haydn\IO\Source\SingleColumnArraySource;
use Quartet\Haydn\Set;

class SelectSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function selectTest()
    {
        $setA = new Set(new SingleColumnArraySource('a', ['abc','def','ghi','jkl']));

        $selectedSet = $setA->select([function($row) {
            return ['name' => $row['a']];
        }]);

        $this->assertThat($selectedSet->count(), $this->equalTo(4));

        $i = new IdenticalSet();
        $e = new EmptySet();

        $temp = $i->product($selectedSet);
        $this->assertThat($temp->count(), $this->equalTo(4));
        $temp = $e->product($selectedSet);
        $this->assertThat($temp->count(), $this->equalTo(0));
    }

    /**
     * @test
     */
    public function selectFromIdenticalIsIdentical()
    {
        $i = new IdenticalSet();
        $selectedIdentical = $i->select([function($row) {
            return ['name' => $row['a']];
        }]);

        $this->assertThat($selectedIdentical->count(), $this->equalTo(1));

        $this->assertThat($selectedIdentical, $this->isInstanceOf(IdenticalSet::class));
    }
}
