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

class HashKeyColumnMapper extends AbstractColumnMapper
{
    /**
     * @param string $name
     * @return integer
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function resolve($name)
    {
        if ($this->map === null) {
            throw new \RuntimeException('map is not initialized.');
        }

        return $this->columnIndex($name);
    }

    /**
     * {@inheritdoc}
     */
    public function makeMap($data)
    {
        if ($this->map === null) {
            $this->map = array_keys($data);
        }

        return $this->map;
    }
}
