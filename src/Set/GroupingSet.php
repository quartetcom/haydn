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
use Quartet\Haydn\SetInterface;

class GroupingSet extends AbstractOperationalSet
{
    /**
     * @var \Callable
     */
    private $headerGenerator;
    /**
     * @var \Callable
     */
    private $memberSetGenerator;
    /**
     * @var \Callable
     */
    private $footerGenerator;

    public function __construct(SetInterface $memberSet, $headerGenerator, $memberSetGenerator, $footerGenerator)
    {
        $this->a = $memberSet;
        $this->headerGenerator = $headerGenerator;
        $this->memberSetGenerator = $memberSetGenerator;
        $this->footerGenerator = $footerGenerator;

        parent::__construct($memberSet);
    }

    /**
     * @return \Traversable
     */
    protected function iterate() {
        $this->a->rewind();
        foreach ($this->a->getIterator() as $r1) {
            foreach ($this->iterateRow($r1) as $detail) {
                yield $detail;
            }
        }
    }

    /**
     * @throws \RuntimeException
     */
    public function count()
    {
        throw new \RuntimeException('This set does not support count.');
    }

    /**
     * @param $r1
     * @return \Traversable
     */
    protected function iterateRow($r1)
    {
        if ($this->headerGenerator) {
            yield call_user_func($this->headerGenerator, $r1);
        }
        $gen = call_user_func($this->memberSetGenerator, $r1);
        foreach ($gen as $r2) {
            yield $r2;
        }
        if ($this->footerGenerator) {
            yield call_user_func($this->footerGenerator, $r1);
        }
    }
}
