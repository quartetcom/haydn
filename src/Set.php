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

namespace Quartet\Haydn;

use Quartet\Haydn\IO\SourceInterface;
use Quartet\Haydn\Matcher\MatcherInterface;
use Quartet\Haydn\Set\FilterSet;
use Quartet\Haydn\Set\ProductSet;
use Quartet\Haydn\Set\SelectSet;

class Set implements \IteratorAggregate
{
    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @var \Generator
     */
    protected $it;

    /**
     * @param SourceInterface $source
     */
    public function __construct(SourceInterface $source = null)
    {
        $this->source = $source;
        $this->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->it;
    }

    /**
     * rewind iterator
     */
    public function rewind()
    {
        $this->source->rewind();
        $this->it = $this->source->getIterator();
    }

    /**
     * @param Set $that
     * @return ProductSet
     */
    public function product(Set $that)
    {
        $product = new ProductSet($this, $that);
        $product->setPrefixing(true);

        return $product;
    }

    /**
     * @param callable[] $selects
     * @return SelectSet
     */
    public function select($selects)
    {
        return new SelectSet($this, $selects);
    }

    /**
     * @param MatcherInterface $matcher
     * @return FilterSet
     */
    public function filter(MatcherInterface $matcher)
    {
        return new FilterSet($this, $matcher);
    }

    /**
     * @param MatcherInterface[] $matchers
     * @return FilterSet[]
     */
    public function devide($matchers)
    {
        $sets = [];
        foreach ($matchers as $key => $matcher)
        {
            $sets[] = $this->filter($matcher);
        }

        return $sets;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $buf = [];
        foreach ($this as $line) {
            $buf[] = $line;
        }

        return $buf;
    }

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->source->setPrefixing($prefixing);
    }
}
