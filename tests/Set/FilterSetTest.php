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

use Quartet\Haydn\Matcher\MatcherInterface;
use Quartet\Haydn\SetInterface;

class FilterSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilterSet
     */
    private $SUT;

    /**
     * @test
     */
    public function tetSetPrefixing()
    {
        $this->SUT->setPrefixing(true);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function testCount()
    {
        $this->SUT->count();
    }

    protected function setUp()
    {
        $this->set = $this->getMock(SetInterface::class);
        $this->matcher = $this->getMock(MatcherInterface::class);
        $this->SUT = new FilterSet($this->set, $this->matcher);
    }
}
