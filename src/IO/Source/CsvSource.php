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

use Quartet\Common\CsvUtil\Csv;
use Quartet\Haydn\IO\ColumnMapper\SimpleArrayColumnMapper;

class CsvSource extends AbstractSource
{
    /**
     * @var Csv
     */
    private $csv;

    public function __construct($name, Csv $csv, $bodyOffset = 1)
    {
        $this->csv = $csv;

        parent::__construct($name, new SimpleArrayColumnMapper([], true));

        $this->setColumnsFromRow($bodyOffset - 1);
    }

    /**
     * {@inheritdoc}
     */
    protected function iterate()
    {
        $this->csv->rewind();
        while (($line = $this->csv->current()) !== false) {
            $row = $this->columnMapper->makeRow($line);
            if ($row === null) {
                $this->csv->next();
                continue;
            }
            yield $row;
            $this->csv->next();
        }
    }

    /**
     * @param integer $rowIndex
     */
    public function setColumnsFromRow($rowIndex)
    {
        $this->csv->setHeaderPosition($rowIndex);
        $row = $this->csv->getHeaderRow();
        $this->columnMapper->setMap($row);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        throw new \RuntimeException('This source does not support count.');
    }
}
