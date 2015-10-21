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

class Matcher implements MatcherInterface
{
    /**
     * @var array
     */
    private $option;

    /**
     * @var array
     */
    private $matchers;

    public function __construct($option)
    {
        $this->option = $option;
        $this->buildMatcher();
    }

    /**
     * Builds matcher array
     */
    private function buildMatcher()
    {
        $this->matchers = [];
        foreach ($this->option as $key => $value) {
            $this->matchers[$key] = $this->buildOneMatcher($key, $value);
        }
    }

    /**
     * @param $key
     * @param $value
     * @return callable
     */
    private function buildOneMatcher($key, $value)
    {
        if (is_callable($value)) {
            return function($current, $row) use ($key, $value) {
                return $current && call_user_func($value, $row[$key], $row);
            };
        }

        return function($current, $row) use ($key, $value) {
            return $current && ((is_array($row) ? $row[$key] : $row) === $value);
        };
    }


    /**
     * {@inheritdoc}
     */
    public function match($row)
    {
        $match = true;
        foreach ($this->matchers as $key => $matcher) {
            $match = $matcher($match, $row);
        }

        return $match;
    }
}
