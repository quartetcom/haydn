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
use Quartet\Haydn\Set\ProductSet;
use Quartet\Haydn\Set\SelectSet;

class Set implements \IteratorAggregate
{
    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var \Generator
     */
    protected $it;

    /**
     * @param SourceInterface $source
     * @param string $as
     */
    public function __construct(SourceInterface $source = null, $as = '')
    {
        $this->source = $source;
        $this->alias = $as;
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
     * rewind iteretor
     */
    public function rewind()
    {
        $this->it = $this->rawIterator();
    }

    /**
     * @return \Generator
     */
    protected function rawIterator()
    {
        foreach ($this->source as $k => $r) {
            $row = $this->makeRow($r);
            yield $row;
        }
    }

    /**
     * @param Set $that
     * @return ProductSet
     */
    public function product(Set $that)
    {
        return new ProductSet($this, $that);
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
     * @param $array
     * @return array
     */
    protected function makeRow($array)
    {
        $row = [];
        foreach ($array as $k => $v) {
            $row[$this->alias . '.' . $k] = $v;
        }

        return $row;
    }
}
