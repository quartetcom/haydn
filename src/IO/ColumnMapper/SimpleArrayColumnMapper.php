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

class SimpleArrayColumnMapper extends AbstractColumnMapper
{
    /**
     * @param array $names
     *  [0] => 'abc',
     *  [1] => 'def',
     *  [2] => 'ghi',
     */
    public function __construct($names, $lazy = false)
    {
        if ($lazy === false && (!is_array($names) || count($names) < 1)) {
            throw new \RuntimeException('Column mapper needs one or more column names.');
        }
        $this->map = $names;
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function resolve($name)
    {
        return $this->columnIndex($name);
    }

    /**
     * {@inheritdoc}
     */
    public function makeMap($data)
    {
        return $this->map;
    }
}
