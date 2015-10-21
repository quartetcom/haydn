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

use Quartet\Haydn\IO\ColumnMapper\NullColumnMapper;
use Quartet\Haydn\IO\ColumnMapperInterface;

class SourceImpl extends AbstractSource
{
    protected function iterate()
    {
    }
    public function count()
    {
    }
}

class AbstractSourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SourceImpl
     */
    private $SUT;

    /**
     * @test
     */
    public function testColumnMapper()
    {
        $mapper = $this->getMock(ColumnMapperInterface::class);
        $mapper->expects($this->once())
            ->method('setSource')
            ->with($this->SUT);

        $this->SUT->setColumnMapper($mapper);
        $result = $this->SUT->getColumnMapper();
        $this->assertThat($result, $this->equalTo($mapper));
    }

    protected function setUp()
    {
        $this->SUT = new SourceImpl('test', new NullColumnMapper());
    }


}
