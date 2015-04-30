<?php
namespace Quartet\Haydn\IO\ColumnMapper;

use Quartet\Haydn\IO\ColumnMapperInterface;

abstract class AbstractColumnMapper implements ColumnMapperInterface
{
    protected $map;

    /**
     * {@inheritdoc}
     */
    public function setMap($map)
    {
        $this->map = $map;
    }
}
