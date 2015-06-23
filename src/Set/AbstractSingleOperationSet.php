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

class AbstractSingleOperationSet extends Set
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
    public function __construct(SetInterface $a, MatcherInterface $matcher = null)
    {
        $this->a = $a;
        $this->matcher = $matcher;
        parent::__construct(null);
    }

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->a->setPrefixing($prefixing);
    }
}