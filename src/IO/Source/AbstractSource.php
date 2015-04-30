<?php
namespace Quartet\Haydn\IO\Source;

use Quartet\Haydn\IO\ColumnMapperInterface;
use Quartet\Haydn\IO\SourceInterface;

abstract class AbstractSource implements SourceInterface
{
    const NAME_DELIMITER = '.';

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
     * @var array
     */
    private $columnNamesCache = null;

    public function __construct($name, ColumnMapperInterface $columnMapper)
    {
        $this->name = $name;
        $this->columnMapper = $columnMapper;
        $this->rewind();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getName();
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
     * @param $data
     * @return array
     */
    protected function makeRow($data)
    {
        if ($this->columnNamesCache === null) {
           $this->initColumnNameCache($data);
        }

        return array_combine($this->columnNamesCache, $data);
    }

    /**
     * @return ColumnMapperInterface
     */
    public function getColumnMapper()
    {
        return $this->columnMapper;
    }

    /**
     * @param array $data
     */
    private function initColumnNameCache($data)
    {
        $map = $this->columnMapper->makeMap($data);
        if (!$map) {
            $map = range(0, count($data) - 1);
        }

        $this->columnNamesCache = array_map(function ($name) {
            return implode(self::NAME_DELIMITER, [$this->name, $name]);
        }, $map);
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
}
