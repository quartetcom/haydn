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

        $result = $this->SUT->resolve('name1');
        $this->assertThat($result, $this->equalTo(array_search('name1', $this->internalMap, true)));

        $result = $this->SUT->resolve('price1');
        $this->assertThat($result, $this->equalTo(array_search('price1', $this->internalMap, true)));
    }

    /**
     * @test
     */
    public function testMakeMap()
    {
        $result = $this->SUT->makeMap([
            'price1' => 100,
            'addresss1' => 'testaddress',
            'email1' => 'testemail',
            'name2' => 'testname']);

        $this->assertThat($result, $this->equalTo(['price1', 'address1', 'email1', 'name1']));

        $result = $this->SUT->makeMap([
            'price1' => 100,
            'addresss1' => 'testaddress',
            'email1' => 'testemail',
            'name1' => 'testname']);

        $this->assertThat($result, $this->equalTo(['price1', 'address1', 'email1', 'name1']));
    }

    protected function setUp()
    {
        $this->map = ['name2' => 'name1'];
        $this->internalMap = ['price1','address1','email1','name1'];
        $this->internalMapper = new SimpleArrayColumnMapper($this->internalMap);
        $this->SUT = new ChainMapper($this->internalMapper, $this->map);
    }
}
