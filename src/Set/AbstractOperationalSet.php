<?php
/*
 * Copyright (c) 2015 GOTO Hidenori <hidenorigoto@gmail.com>,
 * All rights reserved.
 *
 * This file is part of Quartet/Haydn.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace Quartet\Haydn\Set;

use Quartet\Haydn\Set;

abstract class AbstractOperationalSet extends Set
{
    protected $a;
    protected $b;

    /**
     * @param Set $a
     * @param Set $b
     */
    public function __construct(Set $a, Set $b)
    {
        $this->a = $a;
        $this->b = $b;
        parent::__construct(null);
    }

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->a->setPrefixing($prefixing);
        $this->b->setPrefixing($prefixing);
    }
}
