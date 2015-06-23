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

class NullColumnMapper extends AbstractColumnMapper
{
    /**
     * {@inheritdoc}
     */
    public function resolve($name)
    {
        throw new \RuntimeException('Cannot resolve with this mapper');
    }

    /**
     * {@inheritdoc}
     */
    public function makeMap($data)
    {
        return range(0, count($data) - 1);
    }
}
