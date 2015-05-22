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

class GroupingSet extends Set
{
    /**
     * @var Set
     */
    private $memberSet;
    /**
     * @var \Callable
     */
    private $headerSelector;
    /**
     * @var \Callable
     */
    private $memberSetGenerator;
    /**
     * @var \Callable
     */
    private $footerSelector;

    public function __construct(Set $memberSet, $headerSelector, $memberSetGenerator, $footerSelector)
    {
        $this->memberSet = $memberSet;
        $this->headerSelector = $headerSelector;
        $this->memberSetGenerator = $memberSetGenerator;
        $this->footerSelector = $footerSelector;

        parent::__construct(null);
    }

    protected function groupingIterator() {
        $this->memberSet->rewind();
        foreach ($this->memberSet->it as $r1) {
            if ($this->headerSelector) {
                yield call_user_func($this->headerSelector, $r1);
            }
            $gen = call_user_func($this->memberSetGenerator, $r1);
            foreach ($gen as $r2) {
                yield $r2;
            }
            if ($this->footerSelector) {
                yield call_user_func($this->footerSelector, $r1);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->it = $this->groupingIterator();
    }

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->memberSet->setPrefixing($prefixing);
    }
}
