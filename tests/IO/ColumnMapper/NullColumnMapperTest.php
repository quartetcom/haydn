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

class NullColumnMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NullColumnMapper
     */
    private $SUT;

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function testResolve()
    {
        $this->SUT->resolve('apple');
    }

    protected function setUp()
    {
        $this->SUT = new NullColumnMapper();
    }
}
