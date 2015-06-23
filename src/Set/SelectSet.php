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

class SelectSet extends Set
{
    /**
     * @var Set
     */
    protected $a;

    /**
     * @var callable
     */
    protected $select;

    /**
     * @param Set $a
     * @param callable[] $selects
     */
    public function __construct(Set $a, $selects)
    {
        $this->a = $a;
        if (!is_array($selects)) {
            $selects = [$selects];
        }
        $this->selects = $selects;
        parent::__construct(null);
    }

    /**
     * @param Set $a
     * @param callable[] $selects
     * @return \Traversable
     */
    protected function selectIterator(Set $a, $selects)
    {
        $a->rewind();
        foreach ($a->it as $r) {
            foreach ($selects as $select) {
                $r2 = $select($r);
                yield $r2;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->it = $this->selectIterator($this->a, $this->selects);
    }

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->a->setPrefixing($prefixing);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->a->count();
    }
}
