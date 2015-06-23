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
use Quartet\Haydn\Set\EmptySet;
use Quartet\Haydn\Set\FilterSet;
use Quartet\Haydn\Set\IdenticalSet;
use Quartet\Haydn\Set\ProductSet;
use Quartet\Haydn\Set\SelectSet;
use Quartet\Haydn\Set\UnionSet;

class Set implements \IteratorAggregate, \Countable
{
    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @var \Traversable
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
        if ($this instanceof IdenticalSet) {
            return $that;
        } elseif ($that instanceof IdenticalSet) {
            return $this;
        } elseif ($this instanceof EmptySet) {
            return $this;
        } elseif ($that instanceof EmptySet) {
            return $that;
        }
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
        if ($this instanceof IdenticalSet) {
            return $this;
        } elseif ($this instanceof EmptySet) {
            return $this;
        }
        return new SelectSet($this, $selects);
    }

    /**
     * @param MatcherInterface $matcher
     * @return FilterSet
     */
    public function filter(MatcherInterface $matcher)
    {
        if ($this instanceof IdenticalSet) {
            return $this;
        } elseif ($this instanceof EmptySet) {
            return $this;
        }
        return new FilterSet($this, $matcher);
    }

    /**
     * @param MatcherInterface[] $matchers
     * @return FilterSet[]
     */
    public function devide($matchers)
    {
        if ($this instanceof IdenticalSet) {
            return $this;
        } elseif ($this instanceof EmptySet) {
            return $this;
        }
        $sets = [];
        foreach ($matchers as $key => $matcher)
        {
            $sets[] = $this->filter($matcher);
        }

        return $sets;
    }

    /**
     * @param Set $that
     * @return UnionSet
     */
    public function union(Set $that)
    {
        if ($this instanceof IdenticalSet) {
            return $that;
        } elseif ($that instanceof IdenticalSet) {
            return $this;
        } elseif ($this instanceof EmptySet) {
            return $that;
        } elseif ($that instanceof EmptySet) {
            return $this;
        }
        return new UnionSet($this, $that);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $buf = [];
        $this->rewind();
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


    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->source->count();
    }
}
