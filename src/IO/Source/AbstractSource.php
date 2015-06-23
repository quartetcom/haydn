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

use Quartet\Haydn\IO\ColumnMapperInterface;
use Quartet\Haydn\IO\SourceInterface;

abstract class AbstractSource implements SourceInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var ColumnMapperInterface
     */
    protected $columnMapper;

    /**
     * @var \Generator
     */
    protected $it;

    /**
     * @var bool
     */
    protected $skipIllegalRow = true;

    public function __construct($name, ColumnMapperInterface $columnMapper)
    {
        $this->name = $name;
        $this->columnMapper = $columnMapper;
        $this->columnMapper->setSource($this);
        $this->rewind();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc]
     */
    public function getIterator()
    {
        return $this->it;
    }

    /**
     * @return \Generator
     */
    abstract protected function iterate();

    public function rewind()
    {
        $this->it = $this->iterate();
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnMapper()
    {
        return $this->columnMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function setColumnMapper(ColumnMapperInterface $mapper)
    {
        $this->columnMapper = $mapper;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $buf = [];
        foreach ($this as $line) {
            $buf[] = $line;
        }

        return $buf;
    }

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing)
    {
        $this->columnMapper->setPrefixing($prefixing);
    }

    /**
     * @return boolean
     */
    public function getSkipIllegalRow()
    {
        return $this->skipIllegalRow;
    }

    /**
     * @param boolean $skip
     */
    public function setSkipIllegalRow($skip)
    {
        $this->skipIllegalRow = $skip;
    }
}
