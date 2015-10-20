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

use Quartet\Haydn\Matcher\ArrayExcludeMatcher;
use Quartet\Haydn\Matcher\Matcher;

class SingleColumnArraySourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testCount()
    {
        $source = new SingleColumnArraySource('test', ['a', 'bbbb', 123]);

        $this->assertThat($source->count(), $this->equalTo(3));
    }

    /**
     * @test
     */
    public function testInterate()
    {
        $data = ['a', 'bbbb', 123];
        $source = new SingleColumnArraySource('test', $data);

        $ret = $source->toArray();

        $this->assertThat(count($ret), $this->equalTo(3));
        $this->assertThat($ret[0]['test'], $this->equalTo('a'));
    }

    /**
     * @test
     */
    public function testFilter()
    {
        $data = range(1,10);
        $source = new SingleColumnArraySource('test', $data);
        $source->filter(new Matcher(['test'=>4]));
        $ret = $source->toArray();

        $this->assertThat($source->count(), $this->equalTo(1));
        $this->assertThat($ret[0]['test'], $this->equalTo(4));
    }

    /**
     * @test
     */
    public function testFilterByExcluded()
    {
        $data = range(1,10);
        $source = new SingleColumnArraySource('test', $data);
        $exclude = new ArrayExcludeMatcher([2,5,9]);
        $source->filter($exclude);

        $this->assertThat($source->count(), $this->equalTo(7));
        $ret = $source->toArray();
        $this->assertThat($ret[0]['test'], $this->equalTo(1));
        $this->assertThat($ret[1]['test'], $this->equalTo(3));
        $this->assertThat($ret[2]['test'], $this->equalTo(4));
        $this->assertThat($ret[3]['test'], $this->equalTo(6));
        $this->assertThat($ret[4]['test'], $this->equalTo(7));
        $this->assertThat($ret[5]['test'], $this->equalTo(8));
        $this->assertThat($ret[6]['test'], $this->equalTo(10));
    }
}
