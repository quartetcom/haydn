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

use Quartet\Haydn\Matcher\MatcherInterface;
use Quartet\Haydn\Set;
use Quartet\Haydn\SetInterface;

class FilterSet extends Set
{
    /**
     * @var SetInterface
     */
    protected $a;

    /**
     * @var MatcherInterface
     */
    protected $matcher;

    /**
     * @param SetInterface $a
     * @param MatcherInterface $matcher
     */
    public function __construct(SetInterface $a, MatcherInterface $matcher)
    {
        $this->a = $a;
        $this->matcher = $matcher;
        parent::__construct(null);
    }

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
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->a->setPrefixing($prefixing);
    }

    /**
     * @throws \RuntimeException
     */
    public function count()
    {
        throw new \RuntimeException('This set does not support count.');
    }
}
