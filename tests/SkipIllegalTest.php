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

class SkipIllegalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testSkipIllegal()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250],
            ["お","か", 50,3000],
        ];

        $mapper1 = new SimpleArrayColumnMapper([
            'char1', 'char2', 'num1', 'num2'
        ]);

        $aSource = new ArraySource('array', $data, $mapper1);
        $aSource->setSupplementColumns(false);
        $aSource->setSkipIllegalRow(true);
        $set = new Set($aSource, 'array');
        $set->setPrefixing(true);

        $result = $set->toArray();

        $this->assertThat($result[0]['array.char1'], $this->equalTo('あ'));
        $this->assertThat($result[0]['array.num1'], $this->equalTo(150));
        $this->assertThat($result[1]['array.char1'], $this->equalTo('お'));
        $this->assertThat($result[1]['array.num2'], $this->equalTo(3000));
    }

    /**
     * @test
     */
    public function testSkipIllegal_setFalse()
    {
        $data = [
            ["あ","い",150,200],
            ["う","え",250],
            ["お","か", 50,3000],
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
