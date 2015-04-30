<?php
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
}
