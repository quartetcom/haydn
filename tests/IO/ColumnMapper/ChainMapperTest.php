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

use Quartet\Haydn\IO\ColumnMapperInterface;

class ChainMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChainMapper
     */
    private $SUT;

    /**
     * @var ColumnMapperInterface
     */
    private $internalMapper;

    private $map, $internalMap;

    /**
     * @test
     */
    public function testResolve()
    {
        $result = $this->SUT->resolve('name2');

        $this->assertThat($result, $this->equalTo(array_search('name1', $this->internalMap, true)));
    }

    protected function setUp()
    {
        $this->map = ['name1' => 'name2'];
        $this->internalMap = ['price1','address1','email1','name1'];
        $this->internalMapper = new SimpleArrayColumnMapper($this->internalMap);
        $this->SUT = new ChainMapper($this->internalMapper, $this->map);
    }
}
