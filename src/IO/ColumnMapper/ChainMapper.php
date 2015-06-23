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

class ChainMapper extends AbstractColumnMapper
{
    /**
     * @var ColumnMapperInterface
     */
    protected $a;

    /**
     * @var array
     */
    protected $map;

    public function __construct(ColumnMapperInterface $a, $map)
    {
        $this->a = $a;
        $this->map = $map;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($name)
    {
        if (($index = array_search($name, $this->map, true)) === false) {
            throw new \InvalidArgumentException('Undefined column name:' . $name);
        }

        return $this->a->resolve($index);
    }

    /**
     * {@inheritdoc}
     */
    public function makeMap($data)
    {
        $innerMap = $this->a->makeMap($data);

        return array_map(function($element) {
            return $this->map[$element];
        }, $innerMap);
    }
}
