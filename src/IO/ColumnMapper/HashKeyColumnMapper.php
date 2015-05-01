<?php
namespace Quartet\Haydn\IO\ColumnMapper;

class HashKeyColumnMapper extends AbstractColumnMapper
{
    /**
     * @param string $name
     * @return integer
     */
    public function resolve($name)
    {
        if ($this->map === null) {
            throw new \RuntimeException('map is not initialized.');
        }
        if (($index = array_search($name, $this->map, true)) === false) {
            throw new \InvalidArgumentException('Undefined column name:' . $name);
        }

        return $index;
    }

    /**
     * {@inheritdoc}
     */
    public function makeMap($data)
    {
        if ($this->map === null) {
            $this->map = array_keys($data);
        }

        return $this->map;
    }
}
