<?php
namespace Quartet\Haydn\IO\Source;

use Quartet\Haydn\IO\ColumnMapper\NullColumnMapper;

class SingleRowSource extends AbstractSource
{
    /**
     * @var array $row
     */
    private $row;

    public function __construct($name, $row)
    {
        $this->row = $row;
        parent::__construct($name, new NullColumnMapper());
    }

    protected function iterate()
    {
        return [$this->row];
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return 1;
    }
}
