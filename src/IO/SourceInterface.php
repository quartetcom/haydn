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

namespace Quartet\Haydn\IO;

interface SourceInterface extends \IteratorAggregate, \Countable
{
    /**
     * Get name of this source specified with constructor args.
     * @return string
     */
    public function getName();

    /**
     * Set name of this source.
     * @param $name
     */
    public function setName($name);

    /**
     * Rewind iterator.
     */
    public function rewind();

    /**
     * Gets all records
     * @return array
     */
    public function toArray();

    /**
     * @param $prefixing
     */
    public function setPrefixing($prefixing);

    /**
     * @return ColumnMapperInterface
     */
    public function getColumnMapper();

    /**
     * @param ColumnMapperInterface $mapper
     */
    public function setColumnMapper(ColumnMapperInterface $mapper);

    /**
     * @return bool
     */
    public function getSkipIllegalRow();

    /**
     * @param bool $skip
     */
    public function setSkipIllegalRow($skip);
}
