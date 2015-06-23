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

use Quartet\Haydn\IO\SourceInterface;

class MapperImpl extends AbstractColumnMapper
{
    public function resolve($name)
    {
    }
    public function makeMap($data)
    {
    }
}

class AbstractColumnMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MapperImpl
     */
    private $SUT;

    /**
     * @test
     */
    public function testSetMap()
    {
        $map = ['aaa', 'bbb'];
        $this->SUT->setMap($map);
        $this->assertThat($this->SUT->hasColumn('aaa'), $this->equalTo(true));
        $this->assertThat($this->SUT->hasColumn('ccc'), $this->equalTo(false));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function testSetSourceMultipleTimesCauseException()
    {
        $source = $this->getMock(SourceInterface::class);
        $this->SUT->setSource($source);
        $this->SUT->setSource($source);
    }

    protected function setUp()
    {
        $this->SUT = new MapperImpl();
    }
}
