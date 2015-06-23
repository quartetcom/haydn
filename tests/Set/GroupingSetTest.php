<?php
/*
 * Copyright (c) 2015 GOTO Hidenori <hidenorigoto@gmail.com>,
 * All rights reserved.
 *
 * This file is part of Quartet\Haydn.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

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
            function ($r) { return ['type' => 'footer', 'name' => $r['k2'] . '#' . $r['k1']]; }
        );

        $all = $g1->toArray();

        $expected = [
            ['type' => 'header', 'name' => 'あいう-abc'],
            ['type' => 'detail', 'content' => 'あいう abc 123'],
            ['type' => 'detail', 'content' => 'あいう abc 456'],
            ['type' => 'footer', 'name' => 'abc#あいう'],
            ['type' => 'header', 'name' => 'あいう-def'],
            ['type' => 'detail', 'content' => 'あいう def 123'],
            ['type' => 'detail', 'content' => 'あいう def 456'],
            ['type' => 'footer', 'name' => 'def#あいう'],
            ['type' => 'header', 'name' => 'かきく-abc'],
            ['type' => 'detail', 'content' => 'かきく abc 123'],
            ['type' => 'detail', 'content' => 'かきく abc 456'],
            ['type' => 'footer', 'name' => 'abc#かきく'],
            ['type' => 'header', 'name' => 'かきく-def'],
            ['type' => 'detail', 'content' => 'かきく def 123'],
            ['type' => 'detail', 'content' => 'かきく def 456'],
            ['type' => 'footer', 'name' => 'def#かきく'],
        ];


        $this->assertThat(count($all), $this->equalTo(count($expected)));

        $this->assertThat($all, $this->equalTo($expected));

        $this->setExpectedException('\RuntimeException');
        $g1->count();
    }

    /**
     * @test
     */
    public function testGroupAndIdentical()
    {
        $k1 = new Set(new SingleColumnArraySource('k1', ['あいう', 'かきく']));
        $k2 = new Set(new SingleColumnArraySource('k2', ['abc', 'def']));

        $g1 = new Set\GroupingSet(
        // Key Set
            $k1,
            // Header Generator
            function ($r) { return ['type' => 'header', 'name' => $r['k1']]; },
            // Detail Set Generator
            function ($r) use ($k2) {
                $set = new Set(new SingleRowSource('k1', $r));
                $resultSet = $set->product($k2)->select([function ($r) {
                    return [
                        'type' => 'detail',
                        'content' => $r['k1'] . ' ' . $r['k2'],
                    ];
                }]);
                return $resultSet;
            },
            // Footer Generator
            null
        );

        $identical = new IdenticalSet();

        $resultSet = $g1->product($identical);

        $all = $resultSet->toArray();

        $expected = [
            ['type' => 'header', 'name' => 'あいう'],
            ['type' => 'detail', 'content' => 'あいう abc'],
            ['type' => 'detail', 'content' => 'あいう def'],
            ['type' => 'header', 'name' => 'かきく'],
            ['type' => 'detail', 'content' => 'かきく abc'],
            ['type' => 'detail', 'content' => 'かきく def'],
        ];

        $this->assertThat($all, $this->equalTo($expected));

        $resultSet = $identical->product($g1);

        $all = $resultSet->toArray();

        $expected = [
            ['type' => 'header', 'name' => 'あいう'],
            ['type' => 'detail', 'content' => 'あいう abc'],
            ['type' => 'detail', 'content' => 'あいう def'],
            ['type' => 'header', 'name' => 'かきく'],
            ['type' => 'detail', 'content' => 'かきく abc'],
            ['type' => 'detail', 'content' => 'かきく def'],
        ];

        $this->assertThat($all, $this->equalTo($expected));



        $resultSet2 = $g1->union($identical);

        $all = $resultSet2->toArray();

        $expected = [
            ['type' => 'header', 'name' => 'あいう'],
            ['type' => 'detail', 'content' => 'あいう abc'],
            ['type' => 'detail', 'content' => 'あいう def'],
            ['type' => 'header', 'name' => 'かきく'],
            ['type' => 'detail', 'content' => 'かきく abc'],
            ['type' => 'detail', 'content' => 'かきく def'],
        ];

        $this->assertThat($all, $this->equalTo($expected));

        $resultSet2 = $identical->union($g1);

        $all = $resultSet2->toArray();

        $expected = [
            ['type' => 'header', 'name' => 'あいう'],
            ['type' => 'detail', 'content' => 'あいう abc'],
            ['type' => 'detail', 'content' => 'あいう def'],
            ['type' => 'header', 'name' => 'かきく'],
            ['type' => 'detail', 'content' => 'かきく abc'],
            ['type' => 'detail', 'content' => 'かきく def'],
        ];

        $this->assertThat($all, $this->equalTo($expected));
    }


    /**
     * @test
     */
    public function testGroupAndEmpty()
    {
        $k1 = new Set(new SingleColumnArraySource('k1', ['あいう', 'かきく']));
        $k2 = new Set(new SingleColumnArraySource('k2', ['abc', 'def']));

        $g1 = new Set\GroupingSet(
        // Key Set
            $k1,
            // Header Generator
            function ($r) { return ['type' => 'header', 'name' => $r['k1']]; },
            // Detail Set Generator
            function ($r) use ($k2) {
                $set = new Set(new SingleRowSource('k1', $r));
                $resultSet = $set->product($k2)->select([function ($r) {
                    return [
                        'type' => 'detail',
                        'content' => $r['k1'] . ' ' . $r['k2'],
                    ];
                }]);
                return $resultSet;
            },
            // Footer Generator
            null
        );

        $empty = new EmptySet();

        $resultSet = $g1->product($empty);

        $all = $resultSet->toArray();

        $expected = [];

        $this->assertThat($all, $this->equalTo($expected));

        $resultSet = $empty->product($g1);

        $all = $resultSet->toArray();

        $expected = [];

        $this->assertThat($all, $this->equalTo($expected));



        $resultSet2 = $g1->union($empty);

        $all = $resultSet2->toArray();

        $expected = [
            ['type' => 'header', 'name' => 'あいう'],
            ['type' => 'detail', 'content' => 'あいう abc'],
            ['type' => 'detail', 'content' => 'あいう def'],
            ['type' => 'header', 'name' => 'かきく'],
            ['type' => 'detail', 'content' => 'かきく abc'],
            ['type' => 'detail', 'content' => 'かきく def'],
        ];

        $this->assertThat($all, $this->equalTo($expected));

        $resultSet2 = $empty->union($g1);

        $all = $resultSet2->toArray();

        $expected = [
            ['type' => 'header', 'name' => 'あいう'],
            ['type' => 'detail', 'content' => 'あいう abc'],
            ['type' => 'detail', 'content' => 'あいう def'],
            ['type' => 'header', 'name' => 'かきく'],
            ['type' => 'detail', 'content' => 'かきく abc'],
            ['type' => 'detail', 'content' => 'かきく def'],
        ];

        $this->assertThat($all, $this->equalTo($expected));
    }
}
