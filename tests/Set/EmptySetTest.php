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

class EmptySetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function emptyProductTest()
    {
        $setA = new Set(new SingleColumnArraySource('a', ['abc','def']));
        $i = new EmptySet();

        $all = $setA->product($i)->toArray();

        $this->assertThat($all, $this->equalTo([]));

        $all = $i->product($setA)->toArray();

        $this->assertThat($all, $this->equalTo([]));
    }
}
