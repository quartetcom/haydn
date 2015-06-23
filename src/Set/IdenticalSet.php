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

namespace Quartet\Haydn\Set;

use Quartet\Haydn\Set;

class IdenticalSet extends Set
{
    public function __construct()
    {
        $this->rewind();
        $this->it = function () {};
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->it = function () {yield 1;};
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return 1;
    }
}
