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

use Quartet\Haydn\Matcher\MatcherInterface;

interface SetInterface extends \IteratorAggregate, \Countable
{
    /**
     * rewind iterator
     */
    public function rewind();

    /**
     * @param SetInterface $that
     * @return SetInterface
     */
    public function product(SetInterface $that);

    /**
     * @param SetInterface $that
     * @return SetInterface
     */
    public function union(SetInterface $that);

    /**
     * @param callable[] $selects
     * @return SetInterface
     */
    public function select($selects);

    /**
     * @param MatcherInterface $matcher
     * @return SetInterface
     */
    public function filter(MatcherInterface $matcher);

    /**
     * @param MatcherInterface[] $matchers
     * @return SetInterface[]
     */
    public function devide($matchers);

    /**
     * @return bool
     */
    public function isIdentical();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @param bool $prefixing
     */
    public function setPrefixing($prefixing);

}
