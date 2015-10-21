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
     * @param ColumnMapperInterface $a
     * @param $map
     *  [internalKey1] => 'mappedTo1',
     *  [internalKey2] => 'mappedTo2',
     *  [internalKey3] => 'mappedTo3',
     */
    public function __construct(ColumnMapperInterface $a, $map)
    {
        $this->a = $a;
        $this->map = $map;
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function resolve($name)
    {
        $index = $this->columnIndex($name);
        return $this->a->resolve($index);
    }

    /**
     * {@inheritdoc}
     */
    public function makeMap($data)
    {
        $innerMap = $this->a->makeMap($data);

        return array_map(function($element) {
            return array_key_exists($element, $this->map) ?
                $this->map[$element] : $element;
        }, $innerMap);
    }
}
