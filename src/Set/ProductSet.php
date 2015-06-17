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

class ProductSet extends Set
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
     * @param Set $a
     * @param Set $b
     * @return \Generator
     */
    protected function productIterator(Set $a, Set $b) {
        $a->rewind();
        foreach ($a->it as $r1) {
            $b->rewind();
            foreach ($b->it as $r2) {
                yield array_merge($r1, $r2);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->it = $this->productIterator($this->a, $this->b);
    }

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->a->setPrefixing($prefixing);
        $this->b->setPrefixing($prefixing);
    }

    public function count()
    {
        return $this->a->count() * $this->b->count();
    }
}
