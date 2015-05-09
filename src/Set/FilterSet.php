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

class FilterSet extends Set
{
    /**
     * @var Set
     */
    protected $a;

    /**
     * @var MatcherInterface
     */
    protected $matcher;

    /**
     * @param Set $a
     * @param MatcherInterface $matcher
     */
    public function __construct(Set $a, MatcherInterface $matcher)
    {
        $this->a = $a;
        $this->matcher = $matcher;
        parent::__construct(null);
    }

    /**
     * @param Set $a
     * @param MatcherInterface $matcher
     * @return \Generator
     */
    protected function filterIterator(Set $a, MatcherInterface $matcher)
    {
        $a->rewind();
        foreach ($a->it as $r) {
            if (!$matcher->match($r)) continue;
            yield $r;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->it = $this->filterIterator($this->a, $this->matcher);
    }

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->a->setPrefixing($prefixing);
    }
}
