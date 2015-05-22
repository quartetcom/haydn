<?php
namespace Quartet\Haydn\IO\Source;

use Quartet\Haydn\IO\ColumnMapper\NullColumnMapper;

class SingleColumnArraySource extends AbstractSource
{
    /**
     * @var array $data
     */
    private $data;

    public function __construct($name, $data)
    {
        $this->data = $data;
        parent::__construct($name, new NullColumnMapper());
    }

    protected function iterate()
    {
        foreach ($this->data as $line) {
            $row = [$this->name => $line];
            yield $row;
        }
    }
}
