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
    protected $aliasMap;

    /**
     * @param ColumnMapperInterface $a
     * @param $map
     *  'mappedTo1' => internalKey1,
     *  'mappedTo2' => internalKey2,
     *  'mappedTo3' => internalKey1, (supports multiple mapping to same internal key)
     */
    public function __construct(ColumnMapperInterface $a, $map)
    {
        $this->a = $a;
        $this->map = array_keys($map);
        $this->aliasMap = $map;
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function resolve($name)
    {
        $name = array_key_exists($name, $this->aliasMap) ? $this->aliasMap[$name] : $name;

        return $this->a->resolve($name);
    }

    /**
     * {@inheritdoc}
     */
    public function makeMap($data)
    {
        $innerMap = $this->a->makeMap($data);

        return array_map(function($element) {
            return array_key_exists($element, $this->aliasMap) ?
                $this->aliasMap[$element] : $element;
        }, $innerMap);
    }
}
