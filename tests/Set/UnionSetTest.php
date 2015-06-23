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
use Quartet\Haydn\Set;

class UnionSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function unionTest()
    {
        $setA = new Set(new SingleColumnArraySource('a', ['あいう','かきく']));
        $setB = new Set(new SingleColumnArraySource('b', ['さしす','たちつ']));

        $all = $setA->union($setB)->toArray();

        $this->assertThat(count($all), $this->equalTo(4));
        $this->assertThat(array_shift($all[0]), $this->equalTo('あいう'));
        $this->assertThat(array_shift($all[1]), $this->equalTo('かきく'));
        $this->assertThat(array_shift($all[2]), $this->equalTo('さしす'));
        $this->assertThat(array_shift($all[3]), $this->equalTo('たちつ'));
    }
}
