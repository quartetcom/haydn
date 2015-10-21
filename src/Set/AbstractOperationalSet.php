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

abstract class AbstractOperationalSet extends Set
{
    /**
     * @var SetInterface
     */
    protected $a;
    /**
     * @var SetInterface
     */
    protected $b;

    /**
     * @param SetInterface $a
     * @param null|SetInterface $b
     */
    public function __construct(SetInterface $a, SetInterface $b = null)
    {
        $this->a = $a;
        $this->b = $b;
        parent::__construct(null);
    }

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->a->setPrefixing($prefixing);
        if ($this->b) {
            $this->b->setPrefixing($prefixing);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->it = $this->iterate();
    }

    /**
     * @return \Traversable
     */
    abstract protected function iterate();
}
