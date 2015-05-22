<?php
namespace Quartet\Haydn\Set;

use Quartet\Haydn\IO\Source\SingleColumnArraySource;
use Quartet\Haydn\IO\Source\SingleRowSource;
use Quartet\Haydn\Set;

class GroupingSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testGrouping()
    {
        $k1 = new Set(new SingleColumnArraySource('k1', ['あいう', 'かきく', 'さしす']));
        $k2 = new Set(new SingleColumnArraySource('k2', ['abc', 'def', 'ghi']));
        $k3 = new Set(new SingleColumnArraySource('k3', ['プログラマ', 'エンジニア', 'PG', 'SE']));

        $g1 = new GroupingSet($k1->product($k2),
            function ($r) { return ['type' => '広告グループ', 'name' => $r['k1'] . '_' . $r['k2']]; },
            function ($r) use ($k3) {
                $set = new Set(new SingleRowSource('k1k2', $r));
                $resultSet = $set->product($k3)->select([function ($r) {
                    return [
                        'type' => 'キーワード',
                        'keyword' => $r['k1'] . ' ' . $r['k2'] . ' ' . $r['k3'],
                    ];
                }]);
                return $resultSet;
            },
            null
        );

        $this->assertThat($g1, $this->isInstanceOf(Set::class));
        $result = $g1->toArray();
        $this->assertThat(count($result), $this->equalTo(45));
    }
}
