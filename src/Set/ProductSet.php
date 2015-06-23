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

class ProductSet extends AbstractOperationalSet
{
    /**
     * @return \Traversable
     */
    protected function productIterator() {
        $this->a->rewind();
        foreach ($this->a->getIterator() as $r1) {
            $this->b->rewind();
            foreach ($this->b->getIterator() as $r2) {
                yield array_merge($r1, $r2);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->it = $this->productIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->a->count() * $this->b->count();
    }
}
