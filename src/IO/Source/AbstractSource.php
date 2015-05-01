<?php
namespace Quartet\Haydn\IO\Source;

use Quartet\Haydn\IO\ColumnMapperInterface;
use Quartet\Haydn\IO\SourceInterface;

abstract class AbstractSource implements SourceInterface
{
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


    public function __construct($name, ColumnMapperInterface $columnMapper)
    {
        $this->name = $name;
        $this->columnMapper = $columnMapper;
        $this->columnMapper->setSource($this);
        $this->rewind();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return ColumnMapperInterface
     */
    public function getColumnMapper()
    {
        return $this->columnMapper;
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
