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

namespace Quartet\Haydn;

use Quartet\Haydn\IO\ColumnMapper\HashKeyColumnMapper;
use Quartet\Haydn\IO\ColumnMapper\NullColumnMapper;
use Quartet\Haydn\IO\ColumnMapper\SimpleArrayColumnMapper;
use Quartet\Haydn\IO\Source\ArraySource;
use Quartet\Haydn\Matcher\Matcher;
use Quartet\Haydn\Matcher\MatcherInterface;
use Quartet\Haydn\Set\EmptySet;
use Quartet\Haydn\Set\IdenticalSet;

class SetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function 配列ソース_エイリアス無し()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250,300],
            ["お","か", 50,3000],
        ];

        $aSource = new ArraySource('array', $data, new SimpleArrayColumnMapper([
            'char1', 'char2', 'num1', 'num2'
        ]));

        $set = new Set($aSource, 'array');

        $this->assertThat($set, $this->isInstanceOf(Set::class));

        $result = [];
        foreach ($set as $record) {
            $result[] = $record;
        }

        $this->assertThat($result[0]['char1'], $this->equalTo('あ'));
        $this->assertThat($result[0]['num1'], $this->equalTo(150));
        $this->assertThat($result[1]['char1'], $this->equalTo('う'));
        $this->assertThat($result[1]['char2'], $this->equalTo('え'));
        $this->assertThat($result[2]['char1'], $this->equalTo('お'));
        $this->assertThat($result[2]['num2'], $this->equalTo(3000));
    }

    /**
     * @test
     */
    public function 配列ソース_エイリアスあり()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250,300],
            ["お","か", 50,3000],
        ];

        $aSource = new ArraySource('array', $data, $mapper = new NullColumnMapper());
        $aSource = new ArraySource('array', $data, $mapper = new NullColumnMapper());

        $set = new Set($aSource, 'array');
        $set->setPrefixing(true);

        $this->assertThat($set, $this->isInstanceOf(Set::class));

        $result = [];
        foreach ($set as $record) {
            $result[] = $record;
        }

        $this->assertThat($result[0]['array.0'], $this->equalTo('あ'));
        $this->assertThat($result[0]['array.2'], $this->equalTo(150));
        $this->assertThat($result[1]['array.0'], $this->equalTo('う'));
        $this->assertThat($result[1]['array.1'], $this->equalTo('え'));
        $this->assertThat($result[2]['array.0'], $this->equalTo('お'));
        $this->assertThat($result[2]['array.3'], $this->equalTo(3000));
    }

    /**
     * @test
     */
    public function testProductSet()
    {
        $fruits = [
            ['name' => 'Apple',  'price' => 100],
            ['name' => 'Banana', 'price' =>  80],
        ];

        $drinks = [
            ['name' => 'Yoghurt', 'price' => 200],
            ['name' => 'Soda',    'price' => 120],
            ['name' => 'Spirit',  'price' => 160],
        ];

        $fruitSet = new Set(new ArraySource('fruit', $fruits, new HashKeyColumnMapper()));
        $drinkSet = new Set(new ArraySource('drink', $drinks, new HashKeyColumnMapper()));

        $fruitDrinkSet = $fruitSet->product($drinkSet);
        $result = $fruitDrinkSet->toArray();

        $this->assertThat(count($result), $this->equalTo(6));
        $this->assertThat($result[0]['fruit.name'], $this->equalTo('Apple'));
        $this->assertThat($result[0]['drink.name'], $this->equalTo('Yoghurt'));
        $this->assertThat($result[1]['fruit.price'], $this->equalTo(100));
        $this->assertThat($result[1]['drink.price'], $this->equalTo(120));
    }

    /**
     * @test
     */
    public function testSelectSet()
    {
        $fruits = [
            ['name' => 'Apple',  'price' => 100],
            ['name' => 'Banana', 'price' =>  80],
        ];

        $drinks = [
            ['name' => 'Yoghurt', 'price' => 200],
            ['name' => 'Soda',    'price' => 120],
            ['name' => 'Spirit',  'price' => 160],
        ];

        $fruitSet = new Set(new ArraySource('fruit', $fruits, new HashKeyColumnMapper()));
        $drinkSet = new Set(new ArraySource('drink', $drinks, new HashKeyColumnMapper()));

        $fruitDrinkSet = $fruitSet->product($drinkSet);
        $fruitDrinkMenu = $fruitDrinkSet->select([function($r){
            return [
                'item' => $r['fruit.name'] . ' ' . $r['drink.name'],
                'price' => $r['fruit.price'] + $r['drink.price']
            ];
        }]);

        $result = $fruitDrinkMenu->toArray();
        $this->assertThat(count($result), $this->equalTo(6));
        $this->assertThat($result[0]['item'], $this->equalTo('Apple Yoghurt'));
        $this->assertThat($result[0]['price'], $this->equalTo(300));
        $this->assertThat($result[4]['item'], $this->equalTo('Banana Soda'));
        $this->assertThat($result[4]['price'], $this->equalTo(200));

        $fruitDrinkMenuWithRev = $fruitDrinkSet->select([function($r){
                return [
                    'item' => $r['fruit.name'] . ' ' . $r['drink.name'],
                    'price' => $r['fruit.price'] + $r['drink.price']
                ];},function($r){
                return [
                    'item' => $r['drink.name'] . ' ' . $r['fruit.name'],
                    'price' => $r['fruit.price'] + $r['drink.price']
                ];}]
        );

        $result = $fruitDrinkMenuWithRev->toArray();
        $this->assertThat(count($result), $this->equalTo(12));
        $this->assertThat($result[10]['item'], $this->equalTo('Banana Spirit'));
        $this->assertThat($result[10]['price'], $this->equalTo(240));
        $this->assertThat($result[11]['item'], $this->equalTo('Spirit Banana'));
        $this->assertThat($result[11]['price'], $this->equalTo(240));
    }

    /**
     * @test
     */
    public function testFilterSet()
    {
        $products = [
            ['name' => 'Apple',   'price' => 100, 'type' => 'fruit'],
            ['name' => 'Yoghurt', 'price' => 200, 'type' => 'drink'],
            ['name' => 'Soda',    'price' => 120, 'type' => 'drink'],
            ['name' => 'Banana',  'price' =>  80, 'type' => 'fruit'],
            ['name' => 'Spirit',  'price' => 160, 'type' => 'drink'],
        ];

        $productSet = new Set(new ArraySource('product', $products, new HashKeyColumnMapper()));

        $fruitSet = $productSet->filter(new Matcher(['type' => 'fruit']));
        $drinkSet = $productSet->filter(new Matcher(['type' => 'drink']));

        $result = $fruitSet->toArray();
        $this->assertThat(count($result), $this->equalTo(2));
        $this->assertThat($result[0]['name'], $this->equalTo('Apple'));
        $this->assertThat($result[0]['price'], $this->equalTo(100));
        $this->assertThat($result[1]['name'], $this->equalTo('Banana'));
        $this->assertThat($result[1]['price'], $this->equalTo(80));

        $result = $drinkSet->toArray();
        $this->assertThat(count($result), $this->equalTo(3));
        $this->assertThat($result[0]['name'], $this->equalTo('Yoghurt'));
        $this->assertThat($result[0]['price'], $this->equalTo(200));
        $this->assertThat($result[1]['name'], $this->equalTo('Soda'));
        $this->assertThat($result[1]['price'], $this->equalTo(120));
        $this->assertThat($result[2]['name'], $this->equalTo('Spirit'));
        $this->assertThat($result[2]['price'], $this->equalTo(160));
    }

    /**
     * @test
     */
    public function testDevide()
    {
        $products = [
            ['name' => 'Apple',   'price' => 100, 'type' => 'fruit'],
            ['name' => 'Yoghurt', 'price' => 200, 'type' => 'drink'],
            ['name' => 'Soda',    'price' => 120, 'type' => 'drink'],
            ['name' => 'Banana',  'price' =>  80, 'type' => 'fruit'],
            ['name' => 'Spirit',  'price' => 160, 'type' => 'drink'],
        ];

        $productSet = new Set(new ArraySource('product', $products, new HashKeyColumnMapper()));

        list($fruitSet, $drinkSet) = $productSet->devide([
            'fruit' => new Matcher(['type' => 'fruit']),
            'drink' => new Matcher(['type' => 'drink']),
        ]);

        $result = $fruitSet->toArray();
        $this->assertThat(count($result), $this->equalTo(2));
        $this->assertThat($result[0]['name'], $this->equalTo('Apple'));
        $this->assertThat($result[0]['price'], $this->equalTo(100));
        $this->assertThat($result[1]['name'], $this->equalTo('Banana'));
        $this->assertThat($result[1]['price'], $this->equalTo(80));

        $result = $drinkSet->toArray();
        $this->assertThat(count($result), $this->equalTo(3));
        $this->assertThat($result[0]['name'], $this->equalTo('Yoghurt'));
        $this->assertThat($result[0]['price'], $this->equalTo(200));
        $this->assertThat($result[1]['name'], $this->equalTo('Soda'));
        $this->assertThat($result[1]['price'], $this->equalTo(120));
        $this->assertThat($result[2]['name'], $this->equalTo('Spirit'));
        $this->assertThat($result[2]['price'], $this->equalTo(160));
    }

    /**
     * @test
     * @group performance
     */
    public function testLarger()
    {
        $setA = new Set(new ArraySource('a', $this->numarray(1000), new NullColumnMapper()), 'a');
        $setB = new Set(new ArraySource('b', $this->strarray(100), new NullColumnMapper()), 'b');

        $abSet = $setA->product($setB);

        $count = 0;
        foreach ($abSet as $ab) {
            $count++;
        }
        $this->assertThat($count, $this->equalTo(100000));
    }

    private function numarray($size)
    {
        $a = [];
        for ($i = 0; $i < $size; $i++)
        {
            $a[] = ['num' => rand(0, 10000)];
        }

        return $a;
    }

    private function strarray($size)
    {
        $a = [];
        for ($i = 0; $i < $size; $i++)
        {
            $a[] = ['char' => array_rand(range('a', 'z'))];
        }

        return $a;
    }

    /**
     * @test
     * @dataProvider 行生成データ
     */
    public function 行生成($data, $mapper, $prefixing, $expected)
    {
        $set = new Set(new ArraySource('test', $data, $mapper));
        $set->setPrefixing($prefixing);

        $result = [];
        foreach ($set as $row) {
            $result[] = $row;
        }

        $this->assertEquals($result, $expected);
    }

    public function 行生成データ()
    {
        return [
            '単純配列プレフィックス無し' => [
                [[1], [2], [3], [4]],
                new NullColumnMapper(),
                false,
                [[0=>1], [0=>2], [0=>3], [0=>4]]
            ],
            '単純配列プレフィックスあり' => [
                [[1], [2], [3], [4]],
                new NullColumnMapper(),
                true,
                [['test.0'=>1], ['test.0'=>2], ['test.0'=>3], ['test.0'=>4]]
            ],
            '連想配列プレフィックスなし' => [
                [['name'=>'a'], ['name'=>'b'], ['name'=>'c'], ['name'=>'d']],
                new HashKeyColumnMapper(),
                false,
                [['name'=>'a'], ['name'=>'b'], ['name'=>'c'], ['name'=>'d']]
            ],
            '連想配列プレフィックスあり' => [
                [['name'=>'a'], ['name'=>'b'], ['name'=>'c'], ['name'=>'d']],
                new HashKeyColumnMapper(),
                true,
                [['test.name'=>'a'], ['test.name'=>'b'], ['test.name'=>'c'], ['test.name'=>'d']]
            ],
        ];
    }

    /**
     * @test
     */
    public function testCount()
    {
        $fruits = [
            ['name' => 'Apple',  'price' => 100],
            ['name' => 'Banana', 'price' =>  80],
        ];

        $drinks = [
            ['name' => 'Yoghurt', 'price' => 200],
            ['name' => 'Soda',    'price' => 120],
            ['name' => 'Spirit',  'price' => 160],
        ];

        $fruitSet = new Set(new ArraySource('fruit', $fruits, new HashKeyColumnMapper()));
        $drinkSet = new Set(new ArraySource('drink', $drinks, new HashKeyColumnMapper()));

        $this->assertThat($fruitSet->count(), $this->equalTo(2));
        $this->assertThat($drinkSet->count(), $this->equalTo(3));

        $temp = $fruitSet->product($drinkSet);
        $this->assertThat($temp->count(), $this->equalTo(6));
        $temp = $fruitSet->union($drinkSet);
        $this->assertThat($temp->count(), $this->equalTo(5));

        $i = new IdenticalSet();
        $e = new EmptySet();

        $temp = $fruitSet->product($i);
        $this->assertThat($temp->count(), $this->equalTo(2));
        $temp = $fruitSet->product($e);
        $this->assertThat($temp->count(), $this->equalTo(0));
        $temp = $e->product($drinkSet);
        $this->assertThat($temp->count(), $this->equalTo(0));

        $temp = $fruitSet->union($i);
        $this->assertThat($temp->count(), $this->equalTo(2));
        $temp = $fruitSet->union($e);
        $this->assertThat($temp->count(), $this->equalTo(2));
        $temp = $i->union($drinkSet);
        $this->assertThat($temp->count(), $this->equalTo(3));
        $temp = $i->union($e);
        $this->assertThat($temp->count(), $this->equalTo(0));
    }

    /**
     * @test
     */
    public function testIdenticalOrEmpty()
    {
        $fruits = [
            ['name' => 'Apple',  'price' => 100],
            ['name' => 'Banana', 'price' =>  80],
        ];

        $drinks = [
            ['name' => 'Yoghurt', 'price' => 200],
            ['name' => 'Soda',    'price' => 120],
            ['name' => 'Spirit',  'price' => 160],
        ];

        $fruitSet = new Set(new ArraySource('fruit', $fruits, new HashKeyColumnMapper()));
        $drinkSet = new Set(new ArraySource('drink', $drinks, new HashKeyColumnMapper()));

        $i = new IdenticalSet();
        $i2 = new IdenticalSet();
        $e = new EmptySet();
        $e2 = new EmptySet();

        $temp = $i->select([]);
        $this->assertThat($temp->isIdentical(), $this->equalTo(true));
        $temp = $e->select([]);
        $this->assertThat($temp->isEmpty(), $this->equalTo(true));

        $matcher = $this->getMock(MatcherInterface::class);
        $temp = $i->filter($matcher);
        $this->assertThat($temp->isIdentical(), $this->equalTo(true));
        $temp = $e->filter($matcher);
        $this->assertThat($temp->isEmpty(), $this->equalTo(true));

        $temp = $i->devide([$matcher]);
        $this->assertThat(count($temp), $this->equalTo(1));
        $this->assertThat($temp[0]->isIdentical(), $this->equalTo(true));
        $temp = $e->devide([$matcher]);
        $this->assertThat(count($temp), $this->equalTo(1));
        $this->assertThat($temp[0]->isEmpty(), $this->equalTo(true));


        $temp = $i->product($i2);
        $this->assertThat($temp->isEmpty(), $this->equalTo(true));

        $temp = $e->union($e2);
        $this->assertThat($temp->isEmpty(), $this->equalTo(true));
    }
}
