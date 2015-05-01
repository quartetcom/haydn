<?php
namespace Quartet\Haydn\IO\ColumnMapper;

class NullColumnMapper extends AbstractColumnMapper
{
    /**
     * {@inheritdoc}
     */
    public function resolve($name)
    {
        throw new \RuntimeException('Cannot resolve with this mapper');
    }

    /**
     * {@inheritdoc}
     */
    public function makeMap($data)
    {
        return range(0, count($data) - 1);
    }
}
