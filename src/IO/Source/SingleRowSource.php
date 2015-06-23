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

class SingleRowSource extends AbstractSource
{
    /**
     * @var array $row
     */
    private $row;

    public function __construct($name, $row)
    {
        $this->row = $row;
        parent::__construct($name, new NullColumnMapper());
    }

    protected function iterate()
    {
        return [$this->row];
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return 1;
    }
}
