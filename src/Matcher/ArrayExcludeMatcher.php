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

namespace Quartet\Haydn\Matcher;

class ArrayExcludeMatcher implements MatcherInterface
{
    /**
     * @var array
     */
    private $option;

    public function __construct($option)
    {
        $this->option = $option;
    }

    /**
     * @return array
     */
    public function getExcludes()
    {
        return $this->option;
    }

    /**
     * {@inheritdoc}
     */
    public function match($row)
    {
        return in_array($row, $this->option);
    }
}
