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
        $k1 = new Set(new SingleColumnArraySource('k1', ['あいう', 'かきく']));
        $k2 = new Set(new SingleColumnArraySource('k2', ['abc', 'def']));
        $k3 = new Set(new SingleColumnArraySource('k3', ['123', '456']));

        $g1 = new Set\GroupingSet(
        // Key Set
            $k1->product($k2),
            // Header Generator
            function ($r) { return ['type' => 'header', 'name' => $r['k1'] . '-' . $r['k2']]; },
            // Detail Set Generator
            function ($r) use ($k3) {
                $set = new Set(new SingleRowSource('k1k2', $r));
                $resultSet = $set->product($k3)->select([function ($r) {
                    return [
                        'type' => 'detail',
                        'content' => $r['k1'] . ' ' . $r['k2'] . ' ' . $r['k3'],
                    ];
                }]);
                return $resultSet;
            },
            // Footer Generator
            null
        );

        $all = $g1->toArray();

        $this->assertThat(count($all), $this->equalTo(12));

        $expected = [
            ['type' => 'header', 'name' => 'あいう-abc'],
            ['type' => 'detail', 'content' => 'あいう abc 123'],
            ['type' => 'detail', 'content' => 'あいう abc 456'],
            ['type' => 'header', 'name' => 'あいう-def'],
            ['type' => 'detail', 'content' => 'あいう def 123'],
            ['type' => 'detail', 'content' => 'あいう def 456'],
            ['type' => 'header', 'name' => 'かきく-abc'],
            ['type' => 'detail', 'content' => 'かきく abc 123'],
            ['type' => 'detail', 'content' => 'かきく abc 456'],
            ['type' => 'header', 'name' => 'かきく-def'],
            ['type' => 'detail', 'content' => 'かきく def 123'],
            ['type' => 'detail', 'content' => 'かきく def 456'],
        ];

        $this->assertThat($all, $this->equalTo($expected));
    }
}
