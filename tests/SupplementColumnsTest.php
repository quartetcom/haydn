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

namespace Quartet\Haydn;

use Quartet\Haydn\Exception\IllegalColumnNumbersException;
use Quartet\Haydn\IO\ColumnMapper\SimpleArrayColumnMapper;
use Quartet\Haydn\IO\Source\ArraySource;

/**
 * @since 1.2.1
 */
class SupplumentColumnsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testSupplementColumns()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250],
            ["お","か"],
        ];

        $mapper1 = new SimpleArrayColumnMapper([
            'char1', 'char2', 'num1', 'num2'
        ]);

        $aSource = new ArraySource('array', $data, $mapper1);
        $aSource->setSupplementColumns(true);
        $set = new Set($aSource, 'array');
        $set->setPrefixing(true);

        $result = $set->toArray();

        $this->assertThat($result[0]['array.char1'], $this->equalTo('あ'));
        $this->assertThat($result[0]['array.num1'], $this->equalTo(150));
        $this->assertThat($result[1]['array.char1'], $this->equalTo('う'));
        $this->assertThat($result[1]['array.num2'], $this->equalTo(''));
        $this->assertThat($result[2]['array.char1'], $this->equalTo('お'));
        $this->assertThat($result[2]['array.num1'], $this->equalTo(''));
        $this->assertThat($result[2]['array.num2'], $this->equalTo(''));
    }

    /**
     * @test
     */
    public function testSupplementColumns_setFalse()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250],
            ["お","か"],
        ];

        $mapper1 = new SimpleArrayColumnMapper([
            'char1', 'char2', 'num1', 'num2'
        ]);

        $aSource = new ArraySource('array', $data, $mapper1);
        $aSource->setSupplementColumns(false);
        $aSource->setSkipIllegalRow(false);
        $set = new Set($aSource, 'array');
        $set->setPrefixing(true);

        $this->setExpectedException(IllegalColumnNumbersException::class);

        $result = $set->toArray();
    }
}
