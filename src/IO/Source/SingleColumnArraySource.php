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

namespace Quartet\Haydn\IO\Source;

use Quartet\Haydn\IO\ColumnMapper\NullColumnMapper;

class SingleColumnArraySource extends AbstractSource
{
    /**
     * @var array $data
     */
    private $data;

    public function __construct($name, $data)
    {
        $this->data = $data;
        parent::__construct($name, new NullColumnMapper());
    }

    /**
     * {@inheritdoc}
     */
    protected function iterate()
    {
        foreach ($this->data as $line) {
            $row = [$this->name => $line];
            yield $row;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->data);
    }
}
