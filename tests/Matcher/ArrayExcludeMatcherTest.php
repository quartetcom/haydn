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

namespace Quartet\Haydn\Matcher;

class ArrayExcludeMatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testMatch()
    {
        $matcher = new ArrayExcludeMatcher([2,5]);

        $result = $matcher->match(2);
        $this->assertThat($result, $this->equalTo(true));

        $result = $matcher->match(3);
        $this->assertThat($result, $this->equalTo(false));
    }
}
