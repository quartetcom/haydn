<?php
namespace Quartet\Haydn\IO\Source;

use Quartet\Common\CsvUtil\Csv;
use Quartet\Haydn\IO\SourceInterface;

class CsvSource implements SourceInterface
{
    /**
     * @var Csv
     */
    private $csv;

    /**
     * @var \Generator
     */
    private $it;

    public function __construct(Csv $csv)
    {
        $this->csv = $csv;
        $this->it = $this->iterate();
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
    private function iterate()
    {
        $this->csv->rewind();
        while (($values = $this->csv->current()) !== false) {
            yield $values;
            $this->csv->next();
        }
    }
}
