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

interface ColumnMapperInterface
{
    /**
     * Resolves column name to index/key
     * @param string $name
     * @return mixed
     */
    public function resolve($name);

    /**
     * Checks if column exists
     * @param $name
     * @return bool
     */
    public function hasColumn($name);

    /**
     * Make and return column name index map
     * @param array $data (row if needed)
     * @return mixed
     */
    public function makeMap($data);

    /**
     * Set column name map array
     * @param array $map
     * @return mixed
     */
    public function setMap($map);

    /**
     * @param SourceInterface $source
     * @return mixed
     */
    public function setSource(SourceInterface $source);

    /**
     * @param $prefixing
     * @return mixed
     */
    public function setPrefixing($prefixing);
}
