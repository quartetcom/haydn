<?php
namespace Quartet\Haydn\IO\Source;

use Quartet\Haydn\IO\ColumnMapperInterface;

class ArraySource extends AbstractSource
{
    /**
     * @var array $data
     */
    private $data;

    public function __construct($name, $data, ColumnMapperInterface $columnMapper)
    {
        $this->data = $data;
        parent::__construct($name, $columnMapper);
    }

    protected function iterate()
    {
        foreach ($this->data as $line) {
            $row = $this->columnMapper->makeRow($line);
            if ($row === null) {
                continue;
            }
            yield $row;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->data);
    }
}
