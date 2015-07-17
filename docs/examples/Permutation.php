<?php
namespace Haydn\Examples;

use Quartet\Haydn\IO\Source\SingleColumnArraySource;
use Quartet\Haydn\IO\Source\SingleRowSource;
use Quartet\Haydn\IO\SourceInterface;
use Quartet\Haydn\Matcher\ArrayExcludeMatcher;
use Quartet\Haydn\Set;

class Permutation
{
    public function generate($indexes, $max = null)
    {
        $max = ($max === null) ? count($indexes) : $max;
        return $this->make(new SingleColumnArraySource('i', $indexes), 0, $max);
    }

    private function make(SourceInterface $source, $count, $max)
    {
        if ($count === $max) return new Set\IdenticalSet();

        $source->setName($count);
        return new Set\GroupingSet(new Set($source), null, function($currentRow) use ($source, $count, $max) {
            $rowSet = new Set(new SingleRowSource('r'.$count, $currentRow));

            $source = clone $source;
            /** @var SingleColumnArraySource $source */
            $source->filter(new ArrayExcludeMatcher($currentRow));

            $restSet = $this->make($source, $count + 1, $max);

            return $rowSet->product($restSet);
        }, null);
    }
}
