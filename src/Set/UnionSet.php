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

class UnionSet extends Set
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
     * @return \Generator
     */
    protected function unionIterator() {
        $this->a->rewind();
        foreach ($this->a->it as $r1) {
            yield $r1;
        }
        $this->b->rewind();
        foreach ($this->b->it as $r2) {
            yield $r2;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->it = $this->unionIterator();
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
