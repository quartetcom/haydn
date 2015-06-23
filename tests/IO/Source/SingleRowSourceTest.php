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

class SingleRowSourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testCount()
    {
        $source = new SingleRowSource('test', ['a', 'bbbb', 123]);

        $this->assertThat($source->count(), $this->equalTo(1));
    }

    /**
     * @test
     */
    public function testInterate()
    {
        $row = ['a', 'bbbb', 123];
        $source = new SingleRowSource('test', $row);

        $ret = $source->toArray();

        $this->assertThat(count($ret), $this->equalTo(1));
        $this->assertThat($ret[0], $this->equalTo($row));
    }
}
