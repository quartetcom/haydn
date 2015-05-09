<?php
namespace Quartet\Haydn\IO\ColumnMapper;

class SimpleArrayColumnMapper extends AbstractColumnMapper
{
    protected $map;

    /**
     * @param array $names
     *  [0] => 'abc',
     *  [1] => 'def',
     *  [2] => 'ghi',
     */
    public function __construct($names, $lazy = false)
    {
        if ($lazy === false && (!is_array($names) || count($names) < 1)) {
            throw new \RuntimeException('Column mapper needs one or more column names.');
        }
        $this->map = $names;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($name)
    {
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
        return $this->map;
    }
}