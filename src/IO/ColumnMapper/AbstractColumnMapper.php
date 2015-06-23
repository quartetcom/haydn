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

namespace Quartet\Haydn\IO\ColumnMapper;

use Quartet\Haydn\IO\ColumnMapperInterface;
use Quartet\Haydn\IO\SourceInterface;

abstract class AbstractColumnMapper implements ColumnMapperInterface
{
    const NAME_DELIMITER = '.';

    /**
     * @var array
     */
    protected $map;

    /**
     * @var SourceInterface
     */
    protected $source = null;

    /**
     * @var array
     */
    protected $columnNamesCache = null;

    /**
     * @var bool
     */
    protected $enablePrefix = false;

    /**
     * {@inheritdoc}
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @param $data
     * @return array
     * @throws \RuntimeException
     */
    public function makeRow($data)
    {
        if ($this->columnNamesCache === null) {
            $this->initColumnNameCache($data);
        }

        if (count($this->columnNamesCache) !== count($data)) {
            if ($this->source->getSkipIllegalRow() === true) {
                return null;
            } else {
                throw new \RuntimeException('illegal column number:'.PHP_EOL. print_r($data, true));
            }
        }

        return array_combine($this->columnNamesCache, $data);
    }

    /**
     * @param array $data
     */
    protected function initColumnNameCache($data)
    {
        $map = static::makeMap($data);

        $this->columnNamesCache = array_map(function ($name) {
            if ($this->enablePrefix) {
                return implode(self::NAME_DELIMITER, [$this->source->getName(), $name]);
            } else {
                return $name;
            }
        }, $map);
    }

    /**
     * {@inheritdoc}
     */
    public function setSource(SourceInterface $source)
    {
        if ($this->source !== null) {
            throw new \RuntimeException('Cannot reuse column mapper for multiple sources.');
        }
        $this->source = $source;
    }

    /**
     * @param $prefixing
     * @return mixed|void
     */
    public function setPrefixing($prefixing)
    {
        $this->enablePrefix = $prefixing;
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumn($name)
    {
        try {
            $this->columnIndex($name);
            $ret = true;
        } catch (\InvalidArgumentException $e) {
            $ret = false;
        }

        return $ret;
    }

    /**
     * @param $name
     * @return int
     * @throws \InvalidArgumentException
     */
    protected function columnIndex($name)
    {
        if (($index = array_search($name, $this->map, true)) === false) {
            throw new \InvalidArgumentException('Undefined column name:' . $name);
        }

        return $index;
    }
}
