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

namespace Quartet\Haydn\IO\ColumnMapper;

class HashKeyColumnMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HashKeyColumnMapper
     */
    private $SUT;

    /**
     * @test
     */
    public function testResolve()
    {
        $this->SUT->makeMap(['name' => 'taro', 'age' => 34]);
        $index = $this->SUT->resolve('name');

        $this->assertThat($index, $this->equalTo(0));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function testResolveUnknownColumn()
    {
        $this->SUT->makeMap(['name' => 'taro', 'age' => 34]);
        $this->SUT->resolve('price');
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function testResolveUninitialized()
    {
        $this->SUT->resolve('name');
    }

    protected function setUp()
    {
        $this->SUT = new HashKeyColumnMapper();
    }
}
