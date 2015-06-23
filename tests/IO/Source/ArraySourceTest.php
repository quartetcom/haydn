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

namespace Quartet\Haydn\IO\Source;

use Quartet\Haydn\IO\ColumnMapper\HashKeyColumnMapper;
use Quartet\Haydn\IO\ColumnMapper\NullColumnMapper;
use Quartet\Haydn\IO\ColumnMapper\SimpleArrayColumnMapper;

class ArraySourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testSimple()
    {
        $data = [
            [1,2,3],
            [4,5,6]
        ];
        $source = new ArraySource('foo', $data, new NullColumnMapper());
        $this->assertThat($source, $this->isInstanceOf(ArraySource::class));
    }

    /**
     * @test
     */
    public function columnName()
    {
        $data = [
            [1,2,3],
            [4,5,6]
        ];

        $columnNames = ['a', 'b', 'c'];

        $source = new ArraySource('foo', $data, new SimpleArrayColumnMapper($columnNames));
        $source->setPrefixing(true);

        $result = [];
        foreach ($source as $line) {
            $result[] = $line;
        }

        $this->assertThat($result[0]['foo.a'], $this->equalTo(1));
        $this->assertThat($result[0]['foo.b'], $this->equalTo(2));
        $this->assertThat($result[1]['foo.b'], $this->equalTo(5));
        $this->assertThat($result[1]['foo.c'], $this->equalTo(6));
    }

    /**
     * @test
     */
    public function hashArray()
    {
        $data = [
            ['p'=>1,'q'=>2,'r'=>3],
            ['p'=>4,'q'=>5,'r'=>6]
        ];

        $source = new ArraySource('bar', $data, new HashKeyColumnMapper());
        $source->setPrefixing(true);

        $result = [];
        foreach ($source as $line) {
            $result[] = $line;
        }

        $this->assertThat($result[0]['bar.p'], $this->equalTo(1));
        $this->assertThat($result[0]['bar.q'], $this->equalTo(2));
        $this->assertThat($result[1]['bar.q'], $this->equalTo(5));
        $this->assertThat($result[1]['bar.r'], $this->equalTo(6));
    }
}
