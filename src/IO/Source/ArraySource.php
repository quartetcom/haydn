<?php
namespace Quartet\Haydn\IO\Source;

use Quartet\Haydn\IO\SourceInterface;

class ArraySource implements SourceInterface
{
    protected $it;

    public function __construct($data)
    {
        $this->it = new \ArrayIterator($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->it;
    }
}
