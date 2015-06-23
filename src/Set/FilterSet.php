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

class FilterSet extends AbstractSingleOperationSet
{
    /**
     * @return \Traversable
     */
    protected function filterIterator()
    {
        $this->a->rewind();
        foreach ($this->a->getIterator() as $r) {
            if (!$this->matcher->match($r)) continue;
            yield $r;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->it = $this->filterIterator();
    }

    /**
     * @throws \RuntimeException
     */
    public function count()
    {
        throw new \RuntimeException('This set does not support count.');
    }
}
