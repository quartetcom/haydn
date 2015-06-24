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
use Quartet\Haydn\SetInterface;

class SelectSet extends AbstractOperationalSet
{
    /**
     * @var \Callable[]
     */
    protected $selects;

    /**
     * @param SetInterface $a
     * @param callable[] $selects
     */
    public function __construct(SetInterface $a, $selects)
    {
        if (!is_array($selects)) {
            $selects = [$selects];
        }
        $this->selects = $selects;
        parent::__construct($a);
    }

    /**
     * @return \Traversable
     */
    protected function iterate()
    {
        $this->a->rewind();
        foreach ($this->a->getIterator() as $r) {
            foreach ($this->selects as $select) {
                $r2 = $select($r);
                yield $r2;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->a->count();
    }
}
