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

class Set implements SetInterface
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
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->source->rewind();
        $this->it = $this->source->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function product(SetInterface $that)
    {
        if ($this->isIdentical() || $that->isIdentical()) {
            return $this->firstOfNotIdentical([$this, $that]);
        }
        if ($this->isEmpty() || $that->isEmpty()) {
            return new EmptySet();
        }

        $product = new ProductSet($this, $that);
        $product->setPrefixing(true);

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function union(SetInterface $that)
    {
        if ($this->isIdentical() || $that->isIdentical()) {
            return $this->firstOfNotIdentical([$this, $that]);
        }
        if ($this->isEmpty() || $that->isEmpty()) {
            return $this->firstOfNotEmpty([$this, $that]);
        }

        return new UnionSet($this, $that);
    }

    /**
     * {@inheritdoc}
     */
    public function select($selects)
    {
        if ($this->isIdentical()) {
            return $this;
        }
        if ($this->isEmpty()) {
            return $this;
        }
        return new SelectSet($this, $selects);
    }

    /**
     * {@inheritdoc}
     */
    public function filter(MatcherInterface $matcher)
    {
        if ($this->isIdentical()) {
            return $this;
        }
        if ($this->isEmpty()) {
            return $this;
        }
        return new FilterSet($this, $matcher);
    }

    /**
     * {@inheritdoc}
     */
    public function devide($matchers)
    {
        if ($this->isIdentical()) {
            return [$this];
        }
        if ($this->isEmpty()) {
            return [$this];
        }
        $sets = [];
        foreach ($matchers as $matcher)
        {
            $sets[] = $this->filter($matcher);
        }

        return $sets;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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

    /**
     * {@inheritdoc}
     */
    public function isIdentical()
    {
        return $this instanceof IdenticalSet;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this instanceof EmptySet;
    }

    /**
     * @param SetInterface[] $sets
     * @return SetInterface
     */
    protected function firstOfNotIdentical($sets)
    {
        foreach ($sets as $set)
        {
            if (!$set->isIdentical()) {
                return $set;
            }
        }

        return new EmptySet();
    }

    /**
     * @param SetInterface[] $sets
     * @return SetInterface
     */
    protected function firstOfNotEmpty($sets)
    {
        foreach ($sets as $set)
        {
            if (!$set->isEmpty()) {
                return $set;
            }
        }

        return new EmptySet();
    }
}
